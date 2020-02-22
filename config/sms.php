<?php

return [

    /*
    |--------------------------------------------------------------------------
    | List of sms drivers
    |--------------------------------------------------------------------------
    |
    | This is a list of possible sms gateways drivers
    |
    */

    "drivers" => [

        "vodafone" => [
            "end_point" => env("VODAFONE_END_POINT"),
            "account_id" => env("VODAFONE_ACCOUNT_ID"),
            "password" => env("VODAFONE_PASSWORD"),
            "secure_hash" => env("VODAFONE_SECURE_HASH"),
            "sender_name" => env("VODAFONE_SENDER_NAME", "Vodafone")
        ]
    ]
];
