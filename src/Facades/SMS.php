<?php

namespace RobustTools\Resala\Facades;

use Illuminate\Support\Facades\Facade;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;

/**
 * Class SMS
 * @method static self via(string $provider)
 * @method static self to($recipients)
 * @method static self message(string $message)
 * @method static SMSDriverResponseInterface send()
 * @see \RobustTools\Resala\SMS
 */
class SMS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }
}
