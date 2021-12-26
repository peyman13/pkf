<?php

namespace App\Services;

use App\Services\PrkarServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
                ->withOptions(['debug' => true])
                ->timeout(self::$_config['timeout'])
                ->get(self::$_config['api_url']."/v1/province");

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
        return $this->config;
    }

    public function getCity()
    {
        return $this->config;
    }
    public function getMunicipality()
    {
        return $this->config;
    }

    public function setEmployee()
    {
        return $this->config;
    }
}
