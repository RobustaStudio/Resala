<?php

namespace RobustTools\Resala\Drivers;

use RobustTools\Resala\Abstracts\Driver;
use RobustTools\Resala\Contracts\SMSDriverInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;
use RobustTools\Resala\Response\InfobipResponse;
use RobustTools\Resala\Support\HTTP;

final class InfobipDriver extends Driver implements SMSDriverInterface
{
    /**
     * @var string|array
     */
    private $recipients;

    private string $message;

    private string $username;

    private string $password;

    private string $senderName;

    private string $endPoint;

    public function __construct(array $config)
    {
        $this->username = $config["username"];
        $this->password = $config["password"];
        $this->senderName = $config["sender_name"];
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

    public function send(): SMSDriverResponseInterface
    {
        $response = (new HTTP())->post($this->endPoint, $this->headers(), $this->payload());

        return new InfobipResponse($response);
    }

    protected function payload(): string
    {
        return json_encode([
            "text" => $this->message,
            "to" => $this->recipients,
            "from" => $this->senderName
        ]);
    }

    /**
     * Set Infobip Driver request headers.
     *
     * @return array|string[]
     */
    protected function headers(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => sprintf("Basic %s", base64_encode($this->username . ':' . $this->password))
        ];
    }
}
