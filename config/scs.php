<?php

return [
    'env' => [
        'local' => [
            'api_url' => env('SCS_URL', true),
            'headers' => [
                "X-API-KEY" => "6zE5y5442MQ29tQb8bKyYBHhSAJZSfEx", 
                "Username" => 'mypkh',
                "Password" => 'QzZB4tKKatPmqNRW',
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
