<?php


namespace RobustTools\SMS\Drivers;

use RobustTools\SMS\Abstracts\Driver;
use RobustTools\SMS\Contracts\SMSServiceProviderDriverInterface;
use RobustTools\SMS\Exceptions\InternalServerErrorException;
use RobustTools\SMS\Exceptions\UnauthorizedException;
use RobustTools\SMS\Support\HTTPClient;

final class ConnekioDriver extends Driver implements SMSServiceProviderDriverInterface
{
    /**
     * @var string
     */
    private $accountId;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string|array
     */
    private $recipients;

    /**
     * @var string
     */
    private $senderName;

    /**
     * @var string
     */
    private $singleSmsEndPoint;

    /**
     * @var string
     */
    private $batchSmsEndPoint;

    public function __construct (array $config)
    {
        $this->accountId = $config["account_id"];
        $this->username = $config["username"];
        $this->password = $config["password"];
        $this->senderName = $config["sender_name"];
        $this->singleSmsEndPoint = $config["single_sms_endpoint"];
        $this->batchSmsEndPoint = $config["batch_sms_endpoint"];
    }

    /**
     * @param string|array $recipients
     * @return string|array
     */
    public function to ($recipients)
    {
        return $this->recipients = $recipients;
    }

    public function message (string $message): string
    {
        return $this->message = $message;
    }

    /**
     * Build connekio request payload.
     *
     * @return string
     */
    public function payload (): string
    {
        if (!$this->isSendingToMultipleRecipients($this->recipients)) {
            json_encode([
                "account_id" => $this->accountId,
                "text" => $this->message,
                "msisdn" => $this->recipients,
                "sender" => $this->senderName
            ]);
        }

        foreach ($this->recipients as $recipient) {
            $mobileList[]['msisdn'] = $recipient;
        }

        return json_encode([
            "account_id" => $this->accountId,
            "text" => $this->message,
            "sender" => $this->senderName,
            "mobile_list" => $mobileList,
        ]);

    }

    /**
     * Encode authorization credentials using base64.
     *
     * @return string
     */
    private function authorization ()
    {
        return base64_encode($this->username . $this->password . $this->accountId);
    }

    /**
     * Set connekio request headers.
     *
     * @return array|string[]
     */
    public function headers (): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $this->authorization()
        ];
    }

    /**
     * Specify request endpoint based on is it sing or batch.
     *
     * @return string
     */
    private function endpoint (): string
    {
        return $this->isSendingToMultipleRecipients($this->recipients)
            ? $this->batchSmsEndPoint
            : $this->singleSmsEndPoint;
    }

    /**
     * @return string
     * @throws UnauthorizedException|InternalServerErrorException
     */
    public function send (): string
    {
        $response = (new HTTPClient())->post($this->endpoint(), $this->headers(), $this->payload());

        return ($response->getstatusCode() == 200)
            ? "Message sent successfully"
            : "Message couldn't be sent";
    }
}
