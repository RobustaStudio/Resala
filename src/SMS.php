<?php

namespace RobustTools\Resala;

use RobustTools\Resala\Contracts\SMSDriverInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;
use RobustTools\Resala\Factories\SMSDriverFactory;

final class SMS
{
    private SMSDriverInterface $driver;

    public function __construct(?string $driver = null, ?string $filePath = null)
    {
        $this->driver = SMSDriverFactory::create($driver, $filePath);
    }

    public function via(string $driver): self
    {
        $this->driver = SMSDriverFactory::create($driver);

        return $this;
    }

    public function to($recipients): self
    {
        $this->driver->to($recipients);

        return $this;
    }

    public function message(string $message): self
    {
        $this->driver->message($message);

        return $this;
    }

    public function send(): SMSDriverResponseInterface
    {
        return $this->driver->send();
    }
}
