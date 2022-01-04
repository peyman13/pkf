<?php

namespace App\GraphQL\Queries;

use App\Services\Prkar as PrkarService;
use App\Trait\Steps;


class Prkar
{
    use Steps;

    protected $SeriveId = "4311";

    public function __invoke($rootValue, array $args): string
    {
        return match ($args['method']) {
            "province" => $this->province($args),
            "competency" => $this->competency($args),
            "city" => $this->city($args),
            "municipality" => $this->city($args),
            "employee" => $this->employee($args),
            "stepone" => $this->confirmService($args),
            "steptwo" => $this->primaryInquiry($args),
            default => 'Invalid Method !',
        };
    }

    protected function province($args)
    {

        $prkarService = new PrkarService();
        return $prkarService->getProvince();
    }

    protected function competency($args)
    {
        $prkarService = new PrkarService();
        return $prkarService->getCompetency();
    }

    protected function city($args)
    {
        $prkarService = new PrkarService();
        return $prkarService->getCity($args['id']);
    }

    protected function municipality($args)
    {
        $prkarService = new PrkarService();
        return $prkarService->getMunicipality($args['id']);
    }    
    
    protected function employee($args)
    {
        $prkarService = new PrkarService();
        return $prkarService->setEmployee($args['data']);
    }    
    

}
