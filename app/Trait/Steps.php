<?php

namespace App\Trait;

use App\Repository\ServiceRepository;
use Illuminate\Support\Facades\Auth;
use App\Services\IPGParsian;

trait Steps
{
    // first step to start 
    protected function confirmService($args)
    {
        $ServiceRepository = new ServiceRepository();
        $id = $ServiceRepository->insertSteps([
            'steps' => "confirmService",
            'users_id' => Auth::user()->id,
            'service_id' => $this->SeriveId,
            'request' => "{}",
            'response' => "{}",
            'created_at' => '2022-01-03 11:40:24',
            'updated_at' => '2022-01-03 11:40:24'
        ]);
        return json_encode(["id" => $id]);
    }

    protected function primaryInquiry($args)
    {
        $ServiceRepository = new ServiceRepository();
        $ServiceRepository->updateState($args['id'], [
            'steps' => "primaryInquiry",
            'request' => json_encode($args['stepTwo'])
        ]);
        return json_encode(["id" => $args['id']]);
    }    
    
    protected function payment($args)
    {
        $Pay = new IPGParsian();
        return json_encode($Pay->getTransaction("234234","23445345","12000"));
    }
}
