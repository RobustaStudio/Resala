<?php

use RobustTools\Resala\Drivers\ConnekioDriver;
use RobustTools\Resala\Drivers\GatewaySA;
use RobustTools\Resala\Drivers\InfobipDriver;
use RobustTools\Resala\Drivers\VodafoneDriver;
use RobustTools\Resala\Drivers\VectoryLinkDriver;

return [

    /*
     * You can specify a default service provider driver here.
     * If it is not set we'll use vodafone as the default driver.
     */
    'default' => env('SMS_DRIVER', 'vodafone'),

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
        ],

        'infobip' => [
            'end_point' => env('INFOBIP_END_POINT'),
            'username' => env('INFOBIP_USERNAME'),
            'password' => env('INFOBIP_PASSWORD'),
            'sender_name' => env('INFOBIP_SENDER_NAME', 'Infobip')
        ],

        'vectory_link' => [
            'end_point' => env('VECTORY_LINK_END_POINT'),
            'username' => env('VECTORY_LINK_USERNAME'),
            'password' => env('VECTORY_LINK_PASSWORD'),
            'sender_name' => env('VECTORY_LINK_SENDER_NAME', 'Vectory Link'),
            'lang' => env('VECTORY_LINK_LANG', 'E')
        ],

        'gateway_sa' => [
            'end_point' => env('GATEWAYSA_END_POINT', 'http://REST.GATEWAY.SA/api/SendSMS'),
            'api_id' => env('GATEWAYSA_API_ID'),
            'api_password' => env('GATEWAYSA_API_PASSWORD'),
            'sms_type' => env('GATEWAYSA_SMS_TYPE', 'T'),
            'encoding' => env('GATEWAYSA_ENCODING', 'T'),
            'sender_id' => env('GATEWAYSA_SENDER_ID'),
        ],
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
        'connekio' => ConnekioDriver::class,
        'infobip' => InfobipDriver::class,
        'vectory_link' => VectoryLinkDriver::class,
        'gateway_sa' => GatewaySA::class
    ],
];
