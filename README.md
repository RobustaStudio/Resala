# Laravel SMS Gateway Integration Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/robust-tools/sms.svg?style=flat-square)](https://packagist.org/packages/robust-tools/sms)
[![Build Status](https://img.shields.io/travis/robust-tools/sms/master.svg?style=flat-square)](https://travis-ci.org/robust-tools/sms)
[![Quality Score](https://img.shields.io/scrutinizer/g/robust-tools/sms.svg?style=flat-square)](https://scrutinizer-ci.com/g/robust-tools/sms)
[![Total Downloads](https://img.shields.io/packagist/dt/robust-tools/sms.svg?style=flat-square)](https://packagist.org/packages/robust-tools/sms)

Laravel SMS Gateway Integration Package

## Supported Providers
- Vodafone SMS Gateway

## Installation

You can install the package via composer:

```bash
composer require robust-tools/sms
```

## Configure
If you are using Laravel 5.5 or higher then you don't need to add the provider and alias.

In your config/app.php file add these two lines.

``` php
# In your providers array.
'providers' => [
    RobustTools\SMS\SMSServiceProvider::class,
],

# In your aliases array.
'aliases' => [
    'Sms' => RobustTools\SMS\Facades\SMS::class,
],
```
You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="RobustTools\SMS\SMSServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
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
```
Add to your .env file

```.dotenv
VODAFONE_END_POINT=https://e3len.vodafone.com.eg/web2sms/sms/submit/
VODAFONE_ACCOUNT_ID=
VODAFONE_PASSWORD=
VODAFONE_SECURE_HASH=
VODAFONE_SENDER_NAME=
```

## Usage

``` php
use RobustTools\SMS\Drivers\VodafoneDriver;

SMS::via(new VodafoneDriver())
        ->to(["010xxxxxxxx"])
        ->message("Hello World")
        ->send()
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mohabdelaziz95@gmail.com instead of using the issue tracker.

## Credits

- [Mohamed AbdElaziz](https://github.com/mohabdelaziz95)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
