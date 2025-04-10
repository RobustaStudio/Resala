<?php

namespace RobustTools\Resala\Support;

use InvalidArgumentException;

final class Config
{
    public const CONFIG_FILE_NAME = "resala";

    private ConfigRepository $config;

    public function __construct(?string $filepath = null)
    {
        $this->config = new ConfigRepository(
            $this->configurations($filepath)
        );
    }

    public function get($key)
    {
        return $this->config->get($key);
    }

    private function configurations(?string $filepath = null)
    {
        // check if this laravel context (means this package is used inside laravel framework).
        // If so then try to load the laravel resala config file if it exist.
        if (function_exists('config_path')) {
            return config(self::CONFIG_FILE_NAME);
        }

        if (is_null($filepath) || !file_exists($filepath)) {
            throw new InvalidArgumentException("config file [$filepath] not found");
        }

        return require $filepath;
    }
}
