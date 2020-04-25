<?php


namespace RobustTools\SMS\Drivers;

use RobustTools\SMS\abstracts\Driver;
use RobustTools\SMS\Contracts\SMSServiceProviderDriverInterface;
use RobustTools\SMS\Exceptions\BadRequestException;
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

    public function __construct ()
    {
        $this->accountId = config("resala.drivers.connekio.account_id");
        $this->username = config("resala.drivers.connekio.username");
        $this->password = config("resala.drivers.connekio.password");
        $this->senderName = config("resala.drivers.connekio.sender_name");
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
     * @return array
     */
    public function payload (): array
    {
        if (!$this->isSendingToMultipleRecipients($this->recipients)) {
            return [
                "body" => json_encode([
                    "account_id" => $this->accountId,
                    "text" => $this->message,
                    "msisdn" => $this->recipients,
                    "sender" => $this->senderName
                ])
            ];
        }

        foreach ($this->recipients as $recipient) {
            $mobileList[]['msisdn'] = $recipient;
        }

        return [
            "body" => json_encode([
                "account_id" => $this->accountId,
                "text" => $this->message,
                "sender" => $this->senderName,
                "mobile_list" => $mobileList,
            ])
        ];

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
            ? config("resala.drivers.connekio.single_sms_endpoint")
            : config("resala.drivers.connekio.batch_sms_endpoint");
    }

    /**
     * @return string
     * @throws UnauthorizedException|BadRequestException|InternalServerErrorException
     */
    public function send (): string
    {
        $response = (new HTTPClient())->post($this->endpoint(), $this->headers(), $this->payload());

        return ($response->getstatusCode() == 200)
            ? "Message sent successfully"
            : "Message couldn't be sent";
    }
}
