<?php

namespace App\Services;

use App\Services\PrkarServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function Safe\json_decode;

class Prkar  implements PrkarServiceInterface
{
    private static $_config = [];
    private static $_response = [];


    private static function _init()
    {
        self::$_config = config('prkar.env.local');
    }

    public function getProvince()
    {
        self::_init();

        try {

            self::$_response = Http::withHeaders([])
                // ->withOptions(['debug' => true])
                ->timeout(self::$_config['timeout'])
                ->get(self::$_config['api_url'] . "/v1/province");

            if (self::$_response->status() == 200) {
                Log::channel('service_success')
                    ->info('Prkar_Service=>getProvince result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return self::$_response;
            } else {
                Log::channel('service_faild')
                    ->emergency('Prkar_Service=>getProvince result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return false;
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('Prkar_Service=>getProvince' . $e->getMessage());
            return false;
        }
    }

    public function getCompetency()
    {
        self::_init();

        try {

            self::$_response = Http::withHeaders([])
                // ->withOptions(['debug' => true])
                ->timeout(self::$_config['timeout'])
                ->get(self::$_config['api_url'] . "/v1/basic/competency");

            if (self::$_response->status() == 200) {
                Log::channel('service_success')
                    ->info('Prkar_Service=>getCompetency result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return self::$_response;
            } else {
                Log::channel('service_faild')
                    ->emergency('Prkar_Service=>getCompetency result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return false;
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('Prkar_Service=>getCompetency' . $e->getMessage());
            return false;
        }
    }

    public function getCity($id)
    {
        self::_init();

        try {

            self::$_response = Http::withHeaders([])
                // ->withOptions(['debug' => true])
                ->timeout(self::$_config['timeout'])
                ->get(self::$_config['api_url'] . "/v1/province/city/{$id}");

            if (self::$_response->status() == 200) {
                Log::channel('service_success')
                    ->info('Prkar_Service=>getCity result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return self::$_response;
            } else {
                Log::channel('service_faild')
                    ->emergency('Prkar_Service=>getCity result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return false;
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('Prkar_Service=>getCity' . $e->getMessage());
            return false;
        }
    }
    public function getMunicipality($id)
    {
        self::_init();

        try {

            self::$_response = Http::withHeaders([])
                // ->withOptions(['debug' => true])
                ->timeout(self::$_config['timeout'])
                ->get(self::$_config['api_url'] . "/v1/province/municipality/{$id}");

            if (self::$_response->status() == 200) {
                Log::channel('service_success')
                    ->info('Prkar_Service=>getMunicipality result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return self::$_response;
            } else {
                Log::channel('service_faild')
                    ->emergency('Prkar_Service=>getMunicipality result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return false;
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('Prkar_Service=>getMunicipality' . $e->getMessage());
            return false;
        }
    }

    public function setEmployee($requestContent)
    {
        self::_init();
        
        try {
            self::$_response = Http::withHeaders([])
                // ->withOptions(['debug' => true])
                ->timeout(self::$_config['timeout'])
                ->post(self::$_config['api_url'] . "/v1/party/employee", $requestContent);
            
            if (self::$_response->status() == 200) {
                Log::channel('service_success')
                    ->info('Prkar_Service=>setEmployee result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return json_encode(self::$_response->json());
            } else {
                Log::channel('service_faild')
                    ->emergency('Prkar_Service=>setEmployee result=' . json_encode(self::$_response, JSON_UNESCAPED_UNICODE));

                return false;
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('Prkar_Service=>setEmployee' . $e->getMessage());
            return false;
        }
    }
}
