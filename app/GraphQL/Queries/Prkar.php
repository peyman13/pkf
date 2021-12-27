<?php

namespace App\GraphQL\Queries;
use Illuminate\Support\Facades\Http;
use App\Services\Prkar as PrkarService;


class Prkar
{
    public function __invoke($rootValue, array $args): string
    {   
        if($args['method'] == "province")
            return $this->province($args);

        if($args['method'] == "competency")
            return $this->competency($args);
        
        if($args['method'] == "city")
            return $this->city($args);
        
        if($args['method'] == "municipality")
            return $this->city($args);
        
    }

    protected function province($args){

        $prkarService = new PrkarService();
        return $prkarService->getProvince();
    }
    
    protected function competency($args){
        return Http::get('http://192.168.22.137/prkar/v1/basic/competency');
    }

    protected function city($args){
        return Http::get("http://192.168.22.137/prkar/v1/province/city/{$args['id']}");
    }  
    
    protected function municipality($args){
        return Http::get("http://192.168.22.137/prkar/v1/province/municipality/{$args['id']}");
    }

    
}
