<?php

return [
    'env' => [
        'local' => [
            'api_url' => env('IBAN_URL', true),
            'headers' => [
                "X-API-KEY" => "icsit_prkar@cZCTa3RcGzZ3pAfRK7rnRsV5zMeK", 
                "Authorization" => 'Basic aWNzaXRfcHJrYXI6alF5QzZ6Rk5WS3lVNjh1QQ=='
            ],
            "auth" => [
                "domain" => "icsit_prkar",
                "pass" => "fac7Ptja3dtYUFWa",
                "service_id" => 100100
            ],
            'debug' => true,
            'timeout' => 30,
            'otp_type' => "num"
        ],
    ],
];
