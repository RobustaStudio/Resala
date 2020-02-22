<?php

namespace RobustTools\SMS;

use RobustTools\SMS\Contracts\SMSServiceProviderDriverInterface;

final class SMSManager
{
    /**
     * @var SMSServiceProviderDriverInterface
     */
    private $smsServiceProviderDriver;

    /**
     * @param SMSServiceProviderDriverInterface $smsServiceProviderDriver
     * @return SMSManager
     */
    public function via (SMSServiceProviderDriverInterface $smsServiceProviderDriver) : SMSManager
    {
        $this->smsServiceProviderDriver = $smsServiceProviderDriver;

        return $this;
    }

    /**
     * @param array $recipients
     * @return $this
     */
    public function to (array $recipients) : SMSManager
    {
        $this->smsServiceProviderDriver->to($recipients);
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function message (string $message) : SMSManager
    {
        $this->smsServiceProviderDriver->message($message);
        return $this;
    }

    /**
     * @return string
     */
    public function send () : string
    {
        return $this->smsServiceProviderDriver->send();
    }
}

