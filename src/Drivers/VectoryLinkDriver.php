<?php
namespace RobustTools\Resala\Drivers;

use RobustTools\Resala\Abstracts\Driver;
use RobustTools\Resala\Contracts\{SMSDriverInterface, SMSDriverResponseInterface};
use RobustTools\Resala\Response\VectoryLinkResponse;
use RobustTools\Resala\Support\HTTP;

final class VectoryLinkDriver extends Driver implements SMSDriverInterface
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

    private string $lang;

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

        return new VectoryLinkResponse($response);
    }

    protected function payload(): array
    {
        return
            [
                "SMSText" => $this->message,
                "SMSReceiver" => $this->recipients,
                "SMSSender" => $this->senderName,
                'SMSLang' => $this->lang,
                'UserName' => $this->username,
                'Password' => $this->password
            ];
    }

    protected function headers(): array
    {
        return [
            'Content-Type' => 'text/xml; charset=utf-8',
            'Content-Length' => 0
        ];
    }
}
