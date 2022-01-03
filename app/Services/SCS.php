<?php
namespace App\Services;

use App\Services\SCSServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
  
class SCS  implements SCSServiceInterface
{

    private static $_config = [];
    private static $_response = [];


    private static function _init()
    {
        self::$_config = config('scs.env.local');
    }

    public function getQuery(int $inquiryType, string $nationalCardSerial, string $nationalCode,string $mobileNumber, string $birthDate,int $inquiryValidDuration)
    {
        self::_init();

        $requestContent = [
            "params" => [
                "inquiryType" => $inquiryType,
                "nationalCardSerial" => $nationalCardSerial,
                "nationalCode" => $nationalCode,
                "mobileNumber" => $mobileNumber,
                "birthDate" => $birthDate,
                "inquiryValidDuration" => $inquiryValidDuration,
            ]
        ];

        try {

            self::$_response = Http::withHeaders(self::$_config['headers'])
                // ->withOptions(['debug' => true])
                ->timeout(self::$_config['timeout'])
                ->post(self::$_config['api_url'], $requestContent['params']);
              
            if (
                self::$_response->status() === 200 &&
                self::$_response->json('code') == "1" &&
                self::$_response->json('message') == "OK" &&
                self::$_response->json('headerStatus') == 200
            ) {
                Log::channel('service_success')
                    ->info('IBAN_Service=>getQuery result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return json_encode(self::$_response['data']);
            } else if (
                self::$_response->status() === 200 &&
                self::$_response->json('code') == "1" &&
                self::$_response->json('message') == "OK" &&
                self::$_response->json('headerStatus') == 401
            ) {
                Log::channel('service_faild')
                    ->emergency('IBAN_Service=>getQuery result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return json_encode(self::$_response['data']);
            } 
            else if (
                self::$_response->json('code') == "-1"
            ) {
                Log::channel('service_faild')
                    ->emergency('IBAN_Service=>getQuery result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return self::$_response['message'];
            }            
            else if (
                self::$_response->status() === 200 &&
                self::$_response->json('code') == "1" &&
                self::$_response->json('message') == "OK" &&
                self::$_response->json('headerStatus') == 400
            ) {
                Log::channel('service_faild')
                    ->emergency('IBAN_Service=>getQuery result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return json_encode(self::$_response['data']);
            }
            else {
                Log::channel('service_faild')
                    ->emergency('IBAN_Service=>getQuery result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return false;
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('IBAN_Service=>getQuery' . $e->getMessage());
            return false;
        }
    }
}