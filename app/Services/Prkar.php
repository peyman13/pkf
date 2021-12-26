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
      self::$_config = config('otp.env.local');
    }

    public function getProvince()
    {
        self::_init();

        try {
            
            self::$_response = Http::withHeaders(self::$_config['headers'])
                // ->withOptions(['debug' => true])
                ->timeout(self::$_config['timeout'])
                ->get('http://192.168.22.137/prkar/v1/province');
            

            $response = self::$_response->json();

            if (
                $response['status'] === true &&
                isset($response['data']) &&
                isset($response['data']['sms_status_code']) &&
                $response['data']['sms_status_code'] == 20

            ) {
                Log::channel('service_success')
                    ->info('OTP_Service=>getOTP result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

                return $response['data']['sms_otp_text'];
            } else {
                Log::channel('service_faild')
                    ->emergency('OTP_Service=>getOTP result=' . json_encode($response, JSON_UNESCAPED_UNICODE));

                return false;
            }
        } catch (\Exception $e) {
            Log::channel('service_faild')
                ->emergency('OTP_Service=>getOTP' . $e->getMessage());
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
