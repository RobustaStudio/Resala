<?php

namespace RobustTools\Resala\Drivers;

use RobustTools\Resala\Abstracts\Driver;
use RobustTools\Resala\Contracts\SMSDriverInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;
use RobustTools\Resala\Response\ConnekioResponse;
use RobustTools\Resala\Support\HTTP;

final class ConnekioDriver extends Driver implements SMSDriverInterface
{
    private string $accountId;

    private string $username;

    private string $password;

    private string $message;

    /** @var string|array */
    private $recipients;

    private string $senderName;

    private string $singleSmsEndPoint;

    private string $batchSmsEndPoint;

    public function __construct(array $config)
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
    public function to($recipients)
    {
        return $this->recipients = $recipients;
    }

    public function message(string $message): string
    {
        return $this->message = $message;
    }

    public function send(): SMSDriverResponseInterface
    {
        $response = HTTP::post($this->endpoint(), $this->headers(), $this->payload());

        return new ConnekioResponse($response);
    }

    protected function payload(): string
    {
        $payload = [
            "account_id" => $this->accountId,
            "text" => $this->message,
            "sender" => $this->senderName
        ];

        $this->toMultiple($this->recipients) ? $payload['mobile_list'] = array_map(
            fn($recipient) => ['msisdn' => $this->formatPhoneNumber($recipient)],
            $this->recipients
        ) : $payload["msisdn"] = $this->formatPhoneNumber($this->recipients);

        return json_encode($payload);
    }

    protected function headers(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => sprintf(
                "Basic %s",
                base64_encode($this->username . ':' . $this->password . ':' . $this->accountId)
            )
        ];
    }

    private function endpoint(): string
    {
        return $this->toMultiple($this->recipients)
            ? $this->batchSmsEndPoint
            : $this->singleSmsEndPoint;
    }

    protected function formatPhoneNumber($phoneNumber): string
    {
        if (substr($phoneNumber, 0, 1) == '0') {
            $phoneNumber = '2' . $phoneNumber;
        }

        return $phoneNumber;
    }
}
