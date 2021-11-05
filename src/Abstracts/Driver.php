<?php
namespace RobustTools\Resala\Abstracts;

use RobustTools\Resala\Contracts\SMSDriverInterface;

abstract class Driver implements SMSDriverInterface
{
    abstract public function __construct(array $config);

    /**
     * @return string|array
    */
    abstract protected function payload();

    abstract protected function headers(): array;

    /**
     * Determine if sending to multiple recipients.
     *
     * @param string|array $recipients
     * @return bool
     */
    public function isSendingToMultipleRecipients($recipients): bool
    {
        return is_array($recipients);
    }
}
