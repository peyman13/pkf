<?php

return [
    'env' => [
        'local' => [
            'api_url' => env('OTP_URL', true),
            'headers' => [
                'Api-Key' => env('OTP_API_KEY', true),
            ],
            'email' => env('OTP_EMAIL', true),
            'password' => env('OTP_PASSWORD', true),
            'debug' => true,
            'timeout' => 30,
            'otp_type' => "num"
        ],
    ],
];
