<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Services\IPGParsianServiceInterface;

class IPGParsian  implements IPGParsianServiceInterface
{
    private static $_config = [];
    private static $_response = [];
    private static $ErrorArray   = [
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


    private static function _init()
    {
        self::$_config = config('ipg-perisan.env.local');
    }

    public function getTransaction(string $orderId, string $customer, string $price)
    {
        self::_init();

        try {
            $key = self::$_config['pg_key'];

            $request = [
                'terminal_id' => self::$_config['terminal'],
                'merchant_id' => self::$_config['merchant'],
                'order_id' => $orderId,
                'revert_url' => self::$_config['revert_url'],
                'customer_id' => $customer,
                'transaction_amount' => $price,
                'date' => date('Y/m/d'),
                'time' => date('H:i:s')
            ];

            $sign = implode("*", $request);

            $sign_hash = hash_hmac('sha256', $sign, pack('H*', $key));
            
            $request[] = ['metadata' => '',
                          'sign' => $sign_hash];

            $IPGurl = self::$_config['IPG_url'];
            $response = Http::asForm()
                        ->post($IPGurl, $request);
                        
            $transaction_id = explode(",", $response)[1];

            if ($response === FALSE) {
                Log::channel('service_faild')
                    ->emergency('IPGParsian_Service=>getIPGParsian result=' . json_encode($response, JSON_UNESCAPED_UNICODE));
                return [];
            }

            if (explode(",", $response)[0] == "00") {

                Log::channel('service_success')
                    ->info('IPGParsian_Service=>getIPGParsian result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

                return [
                    "transactionId" => $transaction_id,
                    "transactionSign" => hash_hmac('sha256', $transaction_id, pack('H*', $key))
                ];
            }
            if (explode(",", $response)[0] != '00') {
                Log::channel('service_faild')
                    ->emergency('IPGParsian_Service=>getIPGParsian result=' . json_encode($this->ErrorArray[explode(",", $response)[0]], JSON_UNESCAPED_UNICODE));

                return $this->ErrorArray[explode(",", $response)[0]];
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('IPGParsian_Service=>getIPGParsian' . $e->getMessage());
            return false;
        }
    }

    // request is array of post parameters that sent by server IPG
    public function getVerify($request)
    {
        $key = "34eaafa27f89c94b9901f33c2b4c71bc78a0ee9e12f118604722785fb25404b3d02cf1123e1daa64d84972d06527166286e0b22579ef3add448c4cde1a2e2224";
        $sign = $request->get('status') . '*' . $request->get('order_id') . '*' . $request->get('transaction_id') . '*' . $request->get('trace')  . '*' . $request->get('rrn');
        $sign_hash = hash_hmac('sha256', $sign, pack('H*', $key));
        if (strtolower($sign_hash) == strtolower($request->get('sign'))) {

            $iurl = "https://pec.shaparak.ir/NewPG/service/payment/transactionConfirm";
            $ch = curl_init();

            $terminal = "71000002";
            $merchant = "213143400000002";
            $transaction_id = $request->get('transaction_id');


            $sign = $terminal . '*' . $merchant . '*' . $transaction_id;
            $sign_hash = hash_hmac('sha256', $sign, pack('H*', $key));

            $data = array(
                'terminal_id' => $terminal,
                'merchant_id' => $merchant,
                'transaction_id' => $transaction_id,
                'sign' => $sign
            );

            curl_setopt($ch, CURLOPT_URL, $iurl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);

            return $result;
        }
    }
}
