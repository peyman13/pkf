<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Services\IPGParsianServiceInterface;

class IPGParsian  implements IPGParsianServiceInterface
{
    public static $_config = [];
    public static $_response = [];
    public static $ErrorArray   = [
        '9102' => 'مبلغ نامعتبر',
        '9104' => ' currency وارد شده صحیح نمیباشد',
        '9201' => ' دارنده کارت از پرداخت انصراف داده  ',
        '9214' => 'شماره تراکنش معتبر نیست ',
        '9215' => 'چرخه تراکنش نقض شده است ',
        '9217' => 'ترامنش دارای مغایرت است ',
        '9219' => ' زمان مورد نظر به پایان رسیده و تراکنش منقضی میباشد',
        '9220' => 'تراکنش قبلا برگشت خورده است ',
        '9221' => ' تراکنش قبلا با موفقیت انجام گردیده است',
        '9222' => ' دسترسی همزمان به تراکنش',
        '9223' => 'خطای غیره منتظره ',
        '9224' => ' قبض قبلا پرداخت شده است',
        '9301' => 'درخواست با امضای دیجیتال مطابقت ندارد ',
        '9302' => 'دسترسی غیر مجاز ',
        '9501' => 'عملیات ناموفق پاسخی از سوئیچ دریافت نشد ',
        '9502' => 'عملیات ناموفق خطای ISO رخ داده ',
        '9503' => 'عملیات ناموفق ',
        '9601' => 'پارامترهای ورودی درست نیست ',
        ''     => ' ',
    ];


    public static function _init()
    {
        self::$_config = config('ipg-parsian.env.local');
    }

    public function getTransaction(string $orderId, string $customer, string $price)
    {
        self::_init();
        try {
            $key = self::$_config['pg_key'];

            $request = [
                'merchant_id' => self::$_config['merchant'],
                'terminal_id' => self::$_config['terminal'],
                'order_id' => $orderId,
                'revert_url' => self::$_config['revert_url'],
                'transaction_amount' => $price,
                'date' => date('Y/m/d'),
                'time' => date('H:i:s')
            ];

            $sign = implode("*", $request);

            $sign_hash = hash_hmac('sha256', $sign, pack('H*', $key));

            $request = array_merge($request, [
                'customer_id' => $customer,
                'metadata' => 'testt sdfasf 131313',
                'sign' => $sign_hash
            ]);

            $IPGurl = self::$_config['IPG_url_transaction_request'];
            $response = Http::asForm()
                ->post($IPGurl, $request);

            $transaction_id = explode(",", $response)[1];

            if ($response === FALSE) {
                Log::channel('service_faild')
                    ->emergency('IPGParsian_Service=>getTransaction result=' . json_encode($response, JSON_UNESCAPED_UNICODE));
                return [];
            }

            if (explode(",", $response)[0] == "00") {

                Log::channel('service_success')
                    ->info('IPGParsian_Service=>getTransaction result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

                return [
                    "transactionId" => $transaction_id,
                    "transactionSign" => hash_hmac('sha256', $transaction_id, pack('H*', $key))
                ];
            }
            if (explode(",", $response)[0] != '00') {
                Log::channel('service_faild')
                    ->emergency('IPGParsian_Service=>getTransaction result=' . json_encode($this->ErrorArray[explode(",", $response)[0]], JSON_UNESCAPED_UNICODE));

                return $this->ErrorArray[explode(",", $response)[0]];
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('IPGParsian_Service=>getTransaction' . $e->getMessage());
            return false;
        }
    }

    // request is array of post parameters that sent by server IPG
    public function getVerify($status, $order_id, $transaction_id, $trace, $rrn, $sign)
    {
        self::_init();
        try {

            $key = self::$_config['pg_key'];

            $request = [
                'status' => $status,
                'order_id' => $order_id,
                'transaction_id' => $transaction_id,
                'trace' => $trace,
                'rrn' => $rrn
            ];

            $sign = implode("*", $request);

            $sign_hash = hash_hmac('sha256', $sign, pack('H*', $key));
            if (strtolower($sign_hash) == strtolower($sign)) {

                $IPGurl = self::$_config['IPG_url_transaction_confirm'];

                $data = [
                    "terminal_id" => self::$_config['terminal'],
                    "merchant_id" => self::$_config['merchant'],
                    "transaction_id" => $transaction_id
                ];

                $sign = implode("*", $data);
                $sign_hash = hash_hmac('sha256', $sign, pack('H*', $key));

                $response = Http::asForm()
                    ->post($IPGurl, $request);

                if ($response === FALSE) {
                    Log::channel('service_faild')
                        ->emergency('IPGParsian_Service=>getVerify result=' . json_encode($response, JSON_UNESCAPED_UNICODE));
                    return [];
                }

                if ($response == "00") {

                    Log::channel('service_success')
                        ->info('IPGParsian_Service=>getVerify result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

                    return $response;
                }
                if ($response != '00') {
                    Log::channel('service_faild')
                        ->emergency('IPGParsian_Service=>getVerify result=' . json_encode($this->ErrorArray[explode(",", $response)[0]], JSON_UNESCAPED_UNICODE));

                    return $this->ErrorArray[$response];
                }
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('IPGParsian_Service=>getVerify' . $e->getMessage());
            return false;
        }
    }
}
