<?php

use RobustTools\SMS\Drivers\ConnekioDriver;
use RobustTools\SMS\Drivers\VodafoneDriver;

return [

    /*
     * You can specify a default service provider driver here.
     * If it is not set we'll use vodafone as the default driver.
     */
    'default' => env('SERVICE_PROVIDER_DRIVER', 'vodafone'),

    /*
    |--------------------------------------------------------------------------
    | List of sms drivers
    |--------------------------------------------------------------------------
    |
    | This is a list of possible sms gateways drivers
    |
    */

    'drivers' => [

        'vodafone' => [
            'end_point' => env('VODAFONE_END_POINT'),
            'account_id' => env('VODAFONE_ACCOUNT_ID'),
            'password' => env('VODAFONE_PASSWORD'),
            'secure_hash' => env('VODAFONE_SECURE_HASH'),
            'sender_name' => env('VODAFONE_SENDER_NAME', 'Vodafone')
        ],

        'connekio' => [
            'single_sms_endpoint' => env('SINGLE_SMS_ENDPOINT'),
            'batch_sms_endpoint' => env('BATCH_SMS_ENDPOINT'),
            'username' => env('CONNEKIO_USERNAME'),
            'password' => env('CONNEKIO_PASSWORD'),
            'account_id' => env('CONNEKIO_ACCOUNT_ID'),
            'sender_name' => env('CONNEKIO_SENDER_NAME')
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Maps
    |--------------------------------------------------------------------------
    |
    |
    | This is a list of Classes that maps to the Drivers above.
    */
    'map' => [
        'vodafone' => VodafoneDriver::class,
        'connekio' => ConnekioDriver::class
    ],
];
