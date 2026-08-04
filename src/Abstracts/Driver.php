<?php
namespace RobustTools\Resala\Abstracts;

use RobustTools\Resala\Contracts\SMSDriverInterface;

abstract class Driver implements SMSDriverInterface
{
    abstract public function __construct(array $config);

    /**
     * Determine if sending to multiple recipients.
     *
     * @param string|array $recipients
     * @return bool
     */
    public function toMultiple($recipients): bool
    {
        return is_array($recipients);
    }

    /**
     * @return string|array
     */
    abstract protected function payload();

    abstract protected function headers(): array;
}
