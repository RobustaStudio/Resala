<?php
namespace RobustTools\SMS\Drivers;

use RobustTools\SMS\Abstracts\Driver;
use RobustTools\SMS\Contracts\SMSServiceProviderDriverInterface;
use RobustTools\SMS\Exceptions\InternalServerErrorException;
use RobustTools\SMS\Exceptions\UnauthorizedException;
use RobustTools\SMS\Support\HTTPClient;
use RobustTools\SMS\Support\VodafoneXMLRequestBodyBuilder;

final class VodafoneDriver extends Driver implements SMSServiceProviderDriverInterface
{
    /**
     * @var string|array
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
     * @param array $config
     */
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

    /**
     * @param string $message
     * @return string
     */
    public function message(string $message): string
    {
        return $this->message = $message;
    }

    /**
     * Build Vodafone request payload.
     *
     * @return string
     */
    public function payload(): string
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

    /**
     * Set Vodafone Driver request headers.
     *
     * @return array|string[]
     */
    public function headers(): array
    {
        return ['Content-Type' => 'application/xml; charset=UTF8'];
    }

    /**
     * @return string
     * @throws UnauthorizedException|InternalServerErrorException
     */
    public function send(): string
    {
        $response = (new HTTPClient())->post($this->endPoint, $this->headers(), $this->payload());

        return $response->Description;
    }

    /**
     * @return string
     */
    private function hashableKey(): string
    {
        $hashableKey = "AccountId=" . $this->accountId . "&Password=" . $this->password;

        if ($this->isSendingToMultipleRecipients($this->recipients)) {
            foreach ($this->recipients as $recipient) {
                $hashableKey .= "&SenderName=" . $this->senderName . "&ReceiverMSISDN=" . $recipient . "&SMSText=" . $this->message;
            }

            return $hashableKey;
        } else {
            return "AccountId=" . $this->accountId . "&Password=" . $this->password . "&SenderName=" . $this->senderName . "&ReceiverMSISDN=" . $this->recipients . "&SMSText=" . $this->message;
        }
    }
}
