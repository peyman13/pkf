<?php
namespace App\Services;

use App\Services\OTPServiceInterface;
  
class OTP  implements OTPServiceInterface
{
    private $config;

    public function __construct(array $data)
    {
      $this->config = $data;
    } 
    public function sendOTP(){
        
    }

}