<?php

return [
    'env' => [
        'local' => [
            'api_url' => env('IBAN_URL', true),
            'headers' => [
                "X-API-KEY" => "@", 
                "Authorization" => 'Basic =='
            ],
            "auth" => [
                "domain" => "",
                "pass" => "",
                "service_id" => 100100
            ],
            'debug' => true,
            'timeout' => 30,
            'otp_type' => "num"
        ],
    ],
];
