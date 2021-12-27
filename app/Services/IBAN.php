<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Services\IBANServiceInterface;

class IBAN  implements IBANServiceInterface
{
    private static $_config = [];
    private static $_response = [];


    private static function _init()
    {
        self::$_config = config('iban.env.local');
    }

    public function isValidIBAN(string $iban, string $nationalCode, string $birthDate)
    {
        self::_init();

        $requestContent = [
            "auth" => self::$_config['auth'],
            "params" => [
                "iban" => $iban,
                "nationalCode" => $nationalCode,
                "birthDate" => $birthDate
            ]
        ];

        try {

            self::$_response = Http::withHeaders(self::$_config['headers'])
                // ->withOptions(['debug' => true])
                ->timeout(self::$_config['timeout'])
                ->post(self::$_config['api_url'], $requestContent);

            $response = self::$_response->json();

            if (
                self::$_response->status() === 200 &&
                self::$_response->json('data')['transactionState'] == "SUCCESS" &&
                (self::$_response->json('data')['respObject'] == "MOST_PROBABLY" || self::$_response->json('data')['respObject'] == "YES") &&
                self::$_response->json('message') == "OK"
            ) {
                Log::channel('service_success')
                    ->info('IBAN_Service=>getIBAN result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

                return true;
            } else if (
                self::$_response->status() === 200 &&
                self::$_response->json('data')['transactionState'] == "SUCCESS" &&
                (self::$_response->json('data')['respObject'] == "UNKNOWN" || self::$_response->json('data')['respObject'] == "NO") &&
                self::$_response->json('message') == "OK"
            ) {
                Log::channel('service_faild')
                    ->emergency('IBAN_Service=>getIBAN result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

                return false;
            } else {
                Log::channel('service_faild')
                    ->emergency('IBAN_Service=>getIBAN result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

                return false;
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('IBAN_Service=>getIBAN' . $e->getMessage());
            return false;
        }
    }
}
