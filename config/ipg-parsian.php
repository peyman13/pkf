<?php

return [
    'env' => [
        'local' => [
            'IPG_url_transaction_request' => "https://pec.shaparak.ir/NewPG/service/payment/transactionRequest",
            'IPG_url_transaction_confirm' => "https://pec.shaparak.ir/NewPG/service/payment/transactionConfirm",
            'pg_key' => "34eaafa27f89c94b9901f33c2b4c71bc78a0ee9e12f118604722785fb25404b3d02cf1123e1daa64d84972d06527166286e0b22579ef3add448c4cde1a2e2224",
            'terminal' => "71000002",
            'merchant' => "213143400000002",
            'revert_url' => "http://cpors.icsdev.ir/test",
            'debug' => true,
            'timeout' => 30,
        ],
    ],
];
