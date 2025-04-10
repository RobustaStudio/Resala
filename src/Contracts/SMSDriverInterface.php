<?php

namespace RobustTools\Resala\Contracts;

interface SMSDriverInterface
{
    /**
     * SMS recipient could be single recipient (string) or a set of recipients (array).
     *
     * @param string|array $recipients
     * @return string|array
     */
    public function to($recipients);

    public function message(string $message): string;

    public function send(): SMSDriverResponseInterface;
}
