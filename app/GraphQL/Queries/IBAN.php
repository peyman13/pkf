<?php

namespace App\GraphQL\Queries;
use App\Services\IBAN as IBANService;

class IBAN
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($_, array $args)
    {
        $ibanService = new IBANService();
        return $ibanService->isValidIBAN($args['iban'], $args['nationalCode'], $args['birthDate']) ? "TRUE" : "FALSE";
    }
}
