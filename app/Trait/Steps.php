<?php 
namespace App\Trait;
use App\Repository\ServiceRepository;

trait Steps
{
    protected function confirmService($args)
    {
        $ServiceRepository = new ServiceRepository();
        $id = $ServiceRepository->insertSteps(['steps' => "confirmService"]);
        return json_encode(["id" => $id]);                                              
    }

    protected function primaryInquiry($args)
    {
        $ServiceRepository = new ServiceRepository();
        $ServiceRepository->updateState($args['id'], ['steps' => "primaryInquiry",
                                                      'request' => json_encode($args['stepTwo'])]);
        return json_encode(["id" => $args['id']]);                                              
    }
}