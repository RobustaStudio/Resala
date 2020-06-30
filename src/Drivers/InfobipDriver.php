<?php
namespace RobustTools\SMS\Drivers;

use RobustTools\SMS\Abstracts\Driver;
use RobustTools\SMS\Contracts\SMSServiceProviderDriverInterface;
use RobustTools\SMS\Exceptions\InternalServerErrorException;
use RobustTools\SMS\Exceptions\UnauthorizedException;
use RobustTools\SMS\Support\HTTPClient;

final class InfobipDriver extends Driver implements SMSServiceProviderDriverInterface
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
     * InfobipDriver constructor.
     * @param array $config
     */
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
    public function headers(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $this->authorization()
        ];
    }

    /**
     * @return string
     * @throws UnauthorizedException|InternalServerErrorException
     */
    public function send(): string
    {
        $response = (new HTTPClient())->post($this->endPoint, $this->headers(), $this->payload());

        return ($response->getstatusCode() == 200)
            ? "Message sent successfully"
            : "Message couldn't be sent";
    }

    /**
     * Encode authorization credentials using base64.
     *
     * @return string
     */
    private function authorization()
    {
        return base64_encode($this->username . ':' . $this->password);
    }
}
