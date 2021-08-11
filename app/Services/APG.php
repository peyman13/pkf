<?php
namespace App\Services;

use App\Services\APGServiceInterface;
  
class APG  implements APGServiceInterface
{
    private $config;

    public function __construct(array $data)
    {
      $this->config = $data;
    }  
 
    public function getRegisterPeymentSaerial()
    {
      return $this->config;
    }
}