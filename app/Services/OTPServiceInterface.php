<?php

namespace App\Services;
  
Interface OTPServiceInterface
{
    public function getOTP(string $mobile);
}