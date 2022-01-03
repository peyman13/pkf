<?php

namespace App\Services;
  
Interface SCSServiceInterface
{
    public function getQuery(int $inquiryType, 
                            string $nationalCardSerial, 
                            string $nationalCode,
                            string $mobileNumber, 
                            string $birthDate,
                            int $inquiryValidDuration);
}