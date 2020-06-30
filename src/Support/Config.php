<?php
namespace RobustTools\SMS\Support;

use RobustTools\SMS\Exceptions\ConfigFileNotFoundException;

class Config
{
    /**
     * Config file name
     */
    const CONFIG_FILE_NAME = "resala";

    /**
     * @var  ConfigRepository
     */
    private $config;

    /**
     * Config constructor.
     *
     * @param null $configFilePath
     * @throws ConfigFileNotFoundException
     */
    public function __construct($configFilePath = null)
    {
        $config = $this->configurations($configFilePath);
        $this->config = new ConfigRepository($config);
    }

    /**
     * @param $key
     *
     * @return  mixed
     */
    public function get($key)
    {
        return $this->config->get($key);
    }

    /**
     * return the correct config directory path
     *
     * @param null $configFilePath
     * @return  mixed|string
     * @throws ConfigFileNotFoundException
     */
    private function configurations($configFilePath = null)
    {
        // check if this laravel context (means this package is used inside laravel framework).
        // If so then try to load the laravel resala config file if it exist.
        if (function_exists('config_path')) {
            return config(self::CONFIG_FILE_NAME);
        }

        if (is_null($configFilePath) || !file_exists($configFilePath)) {
            throw new ConfigFileNotFoundException();
        }

        return require $configFilePath;
    }
}
