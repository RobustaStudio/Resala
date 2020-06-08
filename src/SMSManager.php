<?php

namespace RobustTools\SMS;

use Exception;
use ReflectionClass;
use ReflectionException;
use RobustTools\SMS\Contracts\SMSServiceProviderDriverInterface;
use RobustTools\SMS\Exceptions\UndefinedDriver;
use RobustTools\SMS\Support\Config;

final class SMSManager
{
    /**
     * @var SMSServiceProviderDriverInterface
     */
    private $smsServiceProviderDriver;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param null $configFilePath
     * @throws Exceptions\ConfigFileNotFoundException
     * @throws ReflectionException
     * @throws UndefinedDriver
     */
    public function __construct ($configFilePath = null)
    {
        $this->config = new Config($configFilePath);
        $this->smsServiceProviderDriver = $this->getDriverInstance($this->config->get('default'));
    }

    /**
     * @param string $smsServiceProviderDriver
     * @return SMSManager
     * @throws UndefinedDriver
     * @throws ReflectionException
     */
    public function via (string $smsServiceProviderDriver): SMSManager
    {
        $this->smsServiceProviderDriver = $this->getDriverInstance($smsServiceProviderDriver);
        return $this;
    }

    /**
     * @param string|array $recipients
     * @return $this
     */
    public function to ($recipients): SMSManager
    {
        $this->smsServiceProviderDriver->to($recipients);
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function message (string $message): SMSManager
    {
        $this->smsServiceProviderDriver->message($message);
        return $this;
    }

    /**
     * @return string
     */
    public function send (): string
    {
        return $this->smsServiceProviderDriver->send();
    }

    /**
     * Validate the given driver.
     *
     * @param string $driver
     * @throws ReflectionException
     * @throws UndefinedDriver
     * @throws Exception
     */
    private function driverValidation (string $driver)
    {
        if (!array_key_exists($driver, $this->config->get('map'))) {
            throw new UndefinedDriver("Unknown Driver");
        }

        if (!(new ReflectionClass($this->config->get('map')[$driver]))->implementsInterface(SMSServiceProviderDriverInterface::class)) {
            throw new Exception("Provided driver must respect SMSServiceProviderDriverInterface contract");
        }
    }

    /**
     * @param string $driver
     * @return SMSServiceProviderDriverInterface
     * @throws ReflectionException
     * @throws UndefinedDriver
     */
    private function getDriverInstance (string $driver): SMSServiceProviderDriverInterface
    {
        $this->driverValidation($driver);
        $class = $this->config->get('map')[$driver];
        return new $class($this->config->get('drivers')[$driver]);
    }
}

