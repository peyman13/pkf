<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use App\Services\OTPServiceInterface;

class OTP  implements OTPServiceInterface
{
  private static $_config = [];
  private static $_response = [];

  public function __construct(array $data)
  {
    $this->config = $data;
  }

  private static function _init()
  {
    self::$_config = config('otp.env.local');
  }

  public function getOTP(string $mobile)
  {
    self::_init();

    $requestContent = [
      'mobile' => $mobile,
      'email' => self::$_config['email'],
      'password' => self::$_config['password'],
      'otp_type' => self::$_config['otp_type'],
    ];

    try {

      self::$_response = Http::withHeaders(self::$_config['headers'])
        // ->withOptions(['debug' => true])
        ->timeout(self::$_config['timeout'])
        ->post(self::$_config['api_url'], $requestContent);

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
      }else{
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
}
