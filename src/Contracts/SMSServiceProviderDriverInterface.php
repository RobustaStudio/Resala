<?php


namespace RobustTools\SMS\Contracts;


interface SMSServiceProviderDriverInterface
{
    /**
     * SMS recipient could be single recipient (string) or a set of recipients (array).
     *
     * @param string|array $recipient
     *
     * @return string|array
     */
    public function to ($recipient);

    /**
     * @param string $message
     *
     * @return string
     */
    public function message (string $message): string;

    /**
     * @return string
     */
    public function send (): string;
}
