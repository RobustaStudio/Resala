# PHP & Laravel SMS Gateway Integration Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/robust-tools/resala.svg?style=flat-square)](https://packagist.org/packages/robust-tools/resala)
[![Total Downloads](https://img.shields.io/packagist/dt/robust-tools/resala.svg?style=flat-square)](https://packagist.org/packages/robust-tools/resala)

**Resala** is a PHP & Laravel Package, (Designed to add support to your laravel or just native php app for sending SMS using local operators in the MENA region Like `Vodafone`, `Infopib`, `Conneckio`, `VectoryLink`).  
**Resala** not just tied to use inside Laravel you can hook it up in any php code

## Supported Providers
- Vodafone SMS Gateway
- Connekio SMS Gateway
- InfoPib SMS Gateway
- Vectory Link SMS Gateway

## Installation

You can install the package via composer:

```bash
composer require robust-tools/resala
```
# Laravel Usage.

## Configure

publish the config file with:

```bash
php artisan vendor:publish --provider="RobustTools\Resala\SMSServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
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
        'vectory_link' => VectoryLink::class
    ],
];
```
## Available Commands:

This adds `vodafone` environment variables to your .env file.
```bash
php artisan resala:make vodafone
```

This adds `connekio` environment variables to your .env file.
```bash
php artisan resala:make connekio
```

This adds `infobip` environment variables to your .env file.
```bash
php artisan resala:make infobip
```

This adds `vectory_link` environment variables to your .env file.
```bash
php artisan resala:make vectory_link
```

## Usage

``` php
SMS::to('010xxxxxxxx')
    ->message("Hello World")
    ->send();

SMS::to(['010xxxxxxxx', '011xxxxxxxx'])
    ->message("Hello World")
    ->send();
```

You can inspect the returned response from your sms provider through:

```php
$response = SMS::to(['010xxxxxxxx', '011xxxxxxxx'])
    ->message("Hello World")
    ->send();

$response->success() // returns bool true on success, false on failure.
$response->body() // returns the returned string response body from the sms provider.
```

you can optionally change the driver using the `via` method
```php
SMS::via('connekio')
    ->to('010xxxxxxxx')
    ->message("Hello World")
    ->send();

SMS::via('infobip')
    ->to('2012xxxxxxxx')
    ->message("Hello World")
    ->send();

SMS::via('vectory_link')
    ->to('2012xxxxxxxx')
    ->message("Hello World")
    ->send();
```

## Outside Laravel
You need to add a config file named `resala.php` in your project directory the contents of the config file must match the schema of the package config file you can find it [HERE](https://github.com/RobustaStudio/Resala/blob/master/config/resala.php).    
just replace the `env(values)` with your driver config values.  

```php
use RobustTools\Resala\SMS;

$configFile = __DIR__ . "/config/resala.php";

(new SMS($configFile))->to(['010995162378', '012345522'])
         ->message("Hello World")
         ->send();
```

IF no configuration file is being passed a `InvalidArgumentException` will be thrown.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mohabdelaziz95@gmail.com instead of using the issue tracker.

## Credits

- [Mohamed AbdElaziz](https://github.com/mohabdelaziz95)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
