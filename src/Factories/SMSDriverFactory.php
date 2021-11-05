<?php
namespace RobustTools\Resala\Factories;
use RobustTools\Resala\Support\Config;

use RobustTools\Resala\Contracts\SMSDriverInterface;

final class SMSDriverFactory
{
    public static function create (?string $driver = null, ?string $filepath = null): SMSDriverInterface
    {
        $config = new Config($filepath);
        $drivers = $config->get('map');
        $driverName = $driver ?? $config->get('default');
        $driver = $drivers[$driverName];

        return  new $driver($config->get('drivers')[$driverName]);
    }
}
