<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Services\IPGParsianServiceInterface;

class IPGParsian  implements IPGParsianServiceInterface
{
    private static $_config = [];
    private static $_response = [];


    private static function _init()
    {
        self::$_config = config('ipg-perisan.env.local');
    }

    public function getTransaction(string $orderId, string $customer, string $price)
    {
        self::_init();
        
        try {
            $key = self::$_config['pg_key'];
            $terminal = self::$_config['terminal'];
            $merchant = self::$_config['merchant'];
            $order_id = $orderId;
            $revert_url = "http://cpors.icsdev.ir/test";
            $customer_id = $customer;
            $transaction_amount = $price;
            $date = date('Y/m/d');
            $time = date('H:i:s');
            $sign = $merchant . '*' . $terminal . '*' . $order_id . '*' . $revert_url . '*' . $transaction_amount . '*' . $date . '*' . $time;;

            $sign_hash = hash_hmac('sha256', $sign, pack('H*', $key));

            $request = [
                'merchant_id' => $merchant,
                'terminal_id' => $terminal,
                'order_id' => $order_id,
                'revert_url' => $revert_url,
                'customer_id' => $customer_id,
                'transaction_amount' => $transaction_amount,
                'date' => $date,
                'time' => $time,
                'metadata' => '',
                'sign' => $sign_hash,
            ];

            $IPGurl = self::$_config['IPG_url'];
            $response = Http::asForm()
                ->post($IPGurl, $request);
            $transaction_id = explode(",", $response)[1];

            return [
                "transactionId" => $transaction_id,
                "transactionSign" => hash_hmac('sha256', $transaction_id, pack('H*', $key))
            ];
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('IBAN_Service=>getIBAN' . $e->getMessage());
            return false;
        }





        //     try {

        //         self::$_response = Http::withHeaders(self::$_config['headers'])
        //             // ->withOptions(['debug' => true])
        //             ->timeout(self::$_config['timeout'])
        //             ->post(self::$_config['api_url'], $requestContent);

        //         $response = self::$_response->json();

        //         if (
        //             self::$_response->status() === 200 &&
        //             self::$_response->json('data')['transactionState'] == "SUCCESS" &&
        //             (self::$_response->json('data')['respObject'] == "MOST_PROBABLY" || self::$_response->json('data')['respObject'] == "YES") &&
        //             self::$_response->json('message') == "OK"
        //         ) {
        //             Log::channel('service_success')
        //                 ->info('IBAN_Service=>getIBAN result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

        //             return true;
        //         } else if (
        //             self::$_response->status() === 200 &&
        //             self::$_response->json('data')['transactionState'] == "SUCCESS" &&
        //             (self::$_response->json('data')['respObject'] == "UNKNOWN" || self::$_response->json('data')['respObject'] == "NO") &&
        //             self::$_response->json('message') == "OK"
        //         ) {
        //             Log::channel('service_faild')
        //                 ->emergency('IBAN_Service=>getIBAN result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

        //             return false;
        //         } else {
        //             Log::channel('service_faild')
        //                 ->emergency('IBAN_Service=>getIBAN result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

        //             return false;
        //         }
        //     } catch (\Exception $e) {
        //         Log::channel('service_faild')
        //             ->emergency('IBAN_Service=>getIBAN' . $e->getMessage());
        //         return false;
        //     }
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
