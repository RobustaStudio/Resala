<?php
namespace RobustTools\Resala\Abstracts;

use RobustTools\Resala\Contracts\SMSDriverInterface;
use RobustTools\Resala\Support\HTTPClient;

abstract class Driver implements SMSDriverInterface
{
    abstract public function __construct(array $config);

    abstract public function payload(): string;

    abstract public function headers(): array;

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

    public function httpClient (): HTTPClient
    {
        return new HTTPClient();
    }
}
