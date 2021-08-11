<?php
namespace App\Services;

use App\Services\SMSServiceInterface;
  
class SMS  implements SMSServiceInterface
{
    private $config;

    public function __construct(array $data)
    {
      $this->config = $data;
    }  
 
    public function sendSMS()
    {
      return $this->config;
    }
}