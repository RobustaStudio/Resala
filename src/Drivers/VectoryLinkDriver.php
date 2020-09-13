<?php

namespace RobustTools\SMS\Drivers;

use RobustTools\SMS\Abstracts\Driver;
use RobustTools\SMS\Contracts\SMSServiceProviderDriverInterface;
use RobustTools\SMS\Exceptions\InternalServerErrorException;
use RobustTools\SMS\Exceptions\UnauthorizedException;
use RobustTools\SMS\Support\HTTPClient;

final class VectoryLinkDriver extends Driver implements SMSServiceProviderDriverInterface
{
    /**
     * @var string|array
     */
    private $recipient;

    /**
     * @var string
     */
    private $message;

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
    private $senderName;

    /**
     * @var string
     */
    private $endPoint;

    /**
     * @var string
     */
    private $lang;

    /**
     * VectoryLinkDriver constructor.
     * @param array $config
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
     * @param string|array $recipient
     * @return string|array
     */
    public function to($recipient)
    {
        return $this->recipient = $recipient;
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
     * Build Infobip request payload.
     *
     * @return string
     */
    public function payload(): string
    {
        return json_encode([
            "SMSText" => $this->message,
            "SMSReceiver" => $this->recipient,
            "SMSSender" => $this->senderName,
            'SMSLang' => $this->lang,
            'UserName' => $this->username,
            'Password' => $this->password
        ]);
    }

    /**
     * Set Infobip Driver request headers.
     *
     * @return array|string[]
     */
    public function headers(): array
    {
        return [
            'Content-Type' => 'text/xml',
            'Content-Length' => 0
        ];
    }

    /**
     * @return string
     * @throws UnauthorizedException|InternalServerErrorException
     */
    public function send(): string
    {
        $response = (new HTTPClient())->get($this->endPoint, $this->headers(), json_decode($this->payload(), true));

        return ($response->getstatusCode() == 200)
            ? "Message sent successfully"
            : "Message couldn't be sent";
    }
}
