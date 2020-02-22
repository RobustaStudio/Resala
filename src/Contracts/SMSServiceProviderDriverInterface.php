<?php


namespace RobustTools\SMS\Contracts;


interface SMSServiceProviderDriverInterface
{
    /**
     * @param array $recipient
     *
     * @return array
     */
    public function to (array $recipient) : array;

    /**
     * @param string $message
     *
     * @return string
     */
    public function message (string $message) : string;


    /**
     * @return string
     */
    public function send () :string;
}
