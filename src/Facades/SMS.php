<?php
namespace RobustTools\Resala\Facades;

use Illuminate\Support\Facades\Facade;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;

/**
 * Class SMSManager
 * @method static self via(string $provider)
 * @method static self to($recipients)
 * @method static self message(string $message)
 * @method static SMSDriverResponseInterface send()
 * @see \RobustTools\Resala\Support\SMSManager
 */
class SMS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }
}
