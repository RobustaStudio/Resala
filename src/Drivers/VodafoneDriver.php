<?php
namespace RobustTools\Resala\Drivers;

use RobustTools\Resala\Abstracts\Driver;
use RobustTools\Resala\Contracts\SMSDriverInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;
use RobustTools\Resala\Response\VodafoneDriverResponse;
use RobustTools\Resala\Support\VodafoneXMLRequestBodyBuilder;

final class VodafoneDriver extends Driver implements SMSDriverInterface
{
    /** @var string|array */
    private $recipients;

    private string $message;

    private string $accountId;

    private string $password;

    private string $senderName;

    private string $secureHash;

    private string $endPoint;

    public function __construct(array $config)
    {
        $this->accountId = $config["account_id"];
        $this->password = $config["password"];
        $this->senderName = $config["sender_name"];
        $this->secureHash = $config["secure_hash"];
        $this->endPoint = $config["end_point"];
    }

    /**
     * @param string|array $recipients
     * @return string|array
     */
    public function to($recipients)
    {
        return $this->recipients = $recipients;
    }

    public function message(string $message): string
    {
        return $this->message = $message;
    }

    public function payload(): string
    {
        return (new VodafoneXMLRequestBodyBuilder(
            $this->accountId,
            $this->password,
            $this->senderName,
            strtoupper(hash_hmac('SHA256', $this->hashableKey(), $this->secureHash)),
            $this->recipients,
            $this->message
        ))->build();
    }

    /**
     * Set Vodafone Driver request headers.
     *
     * @return array|string[]
     */
    public function headers(): array
    {
        return ['Content-Type' => 'application/xml; charset=UTF8'];
    }

    public function send(): SMSDriverResponseInterface
    {
        $response = $this->httpClient()->post($this->endPoint, $this->headers(), $this->payload());

        return new VodafoneDriverResponse($response);
    }

    private function hashableKey(): string
    {
        $hashableKey = sprintf("AccountId=%s&Password=%s", $this->accountId, $this->password);

        if ($this->isSendingToMultipleRecipients($this->recipients)) {

            foreach ($this->recipients as $recipient) {
                $hashableKey .= sprintf("&SenderName=%s&ReceiverMSISDN=%s&SMSText=%s", $this->senderName, $recipient, $this->message);
            }

            return $hashableKey;
        } else {
            return sprintf("AccountId=%s&Password=%s&SenderName=%s&ReceiverMSISDN=%s&SMSText=%s", $this->accountId, $this->password, $this->senderName, $this->recipients, $this->message);
        }
    }
}
