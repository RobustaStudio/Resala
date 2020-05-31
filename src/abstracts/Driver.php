<?php


namespace RobustTools\SMS\abstracts;

use RobustTools\SMS\Contracts\SMSServiceProviderDriverInterface;

abstract class Driver implements SMSServiceProviderDriverInterface
{

    /**
     * set every driver conf.
     *
     * Driver constructor.
     */
    abstract public function __construct ();

    /**
     * Build Driver request payload.
     *
     * @return string
     */
    abstract public function payload (): string ;

    /**
     * Set Driver request headers.
     *
     * @return array
     */
    abstract public function headers (): array;

    /**
     * Determine if sending to multiple recipients.
     *
     * @param $recipients
     * @return bool
     */
    public function isSendingToMultipleRecipients ($recipients): bool
    {
        return is_array($recipients);
    }
}
