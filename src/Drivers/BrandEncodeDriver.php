<?php

namespace RobustTools\Resala\Drivers;

use RobustTools\Resala\Abstracts\Driver;
use RobustTools\Resala\Contracts\SMSDriverInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;
use RobustTools\Resala\Response\BrandEncodeResponse;
use RobustTools\Resala\Support\HTTP;

final class BrandEncodeDriver extends Driver implements SMSDriverInterface
{
    /**
     * @var string|array
     */
    private $recipients;

    /**
     * @var string
     */
    private string $message;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var string
     */
    private string $senderName;

    /**
     * @var string
     */
    private string $endPoint;

    /**
     * @var string
     */
    private string $lang;

    /**
     * @param  array $config
     */
    public function __construct(array $config)
    {
        $this->username = $config["username"];
        $this->password = $config["password"];
        $this->senderName = $config["sender_name"];
        $this->endPoint = $config["end_point"];
        $this->lang = $config["lang"];
    }

    /**
     * @param string|array $recipients
     * @return string|array
     */
    public function to($recipients)
    {
        return $this->recipients = $this->toMultiple($recipients)
            ? implode(', ', $recipients)
            : $recipients;
    }

    public function message(string $message): string
    {
        return $this->message = $message;
    }

    public function send(): SMSDriverResponseInterface
    {
        $response = HTTP::get($this->endPoint, $this->headers(), $this->payload());

        return new BrandEncodeResponse($response);
    }

    protected function payload(): array
    {
        return
            [
                "message" => $this->message,
                "receiver" => $this->recipients,
                "sender" => $this->senderName,
                'language' => $this->lang,
                'username' => $this->username,
                'password' => $this->password
            ];
    }

    protected function headers(): array
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }
}
