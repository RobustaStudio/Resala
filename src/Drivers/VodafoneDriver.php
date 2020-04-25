<?php


namespace RobustTools\SMS\Drivers;

use RobustTools\SMS\Contracts\SMSServiceProviderDriverInterface;
use RobustTools\SMS\Exceptions\VodafoneInvalidRequestException;
use RobustTools\SMS\Support\HTTPClient;
use RobustTools\SMS\Support\VodafoneXMLRequestBodyBuilder;

final class VodafoneDriver implements SMSServiceProviderDriverInterface
{
    /**
     * @var array
     */
    private $recipients;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $accountId;

    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $senderName;

    /**
     * @var string
     */
    private $secureHash;
    /**
     * @var string
     */
    private $endPoint;

    /**
     * VodafoneDriver constructor.
     */
    public function __construct ()
    {
        $this->accountId = config("sms.drivers.vodafone.account_id");
        $this->password = config("sms.drivers.vodafone.password");
        $this->senderName = config("sms.drivers.vodafone.sender_name");
        $this->secureHash = config("sms.drivers.vodafone.secure_hash");
        $this->endPoint = config("sms.drivers.vodafone.end_point");
    }


    /**
     * @param array $recipients
     * @return array
     */
    public function to (array $recipients): array
    {
        return $this->recipients = $recipients;
    }

    /**
     * @param string $message
     * @return string
     */
    public function message (string $message): string
    {
        return $this->message = $message;
    }

    /**
     * @return string
     * @throws VodafoneInvalidRequestException
     */
    public function send (): string
    {
        $response = (new HTTPClient())->post($this->endPoint, ['Content-Type' => 'application/xml; charset=UTF8'], $this->payload());

        if ($response->ResultStatus == "INVALID_REQUEST") {
            throw new VodafoneInvalidRequestException($response->Description);
        }

        if ($response->ResultStatus == "SUCCESS") {
            return "Message sent successfully";
        }

        return $response->ResultStatus;
    }

    /**
     * @return string
     */
    private function hashableKey (): string
    {
        $hashableKey = "AccountId=" . $this->accountId . "&Password=" . $this->password;
        foreach ($this->recipients as $recipient) {
            $hashableKey .= "&SenderName=" . $this->senderName . "&ReceiverMSISDN=" . $recipient . "&SMSText=" . $this->message;
        }
        return $hashableKey;
    }

    /**
     * Build Vodafone request payload.
     *
     * @return string
     */
    public function payload ()
    {
        $secureHash = strtoupper(hash_hmac('SHA256', $this->hashableKey(), $this->secureHash));

        return (new VodafoneXMLRequestBodyBuilder(
            $this->accountId,
            $this->password,
            $this->senderName,
            $secureHash,
            $this->recipients,
            $this->message
        ))->build();
    }
}
