<?php

namespace App\GraphQL\Queries;

use App\Services\SCS as SCService;

class SCS
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $ibanService = new SCService();
        return $ibanService->getQuery($args['inquiryType'], 
                                        $args['nationalCardSerial'], 
                                        $args['nationalCode'], 
                                        $args['mobileNumber'], 
                                        $args['birthDate'], 
                                        $args['inquiryValidDuration']);
    }
}
