<?php
namespace RobustTools\Resala\Drivers;

use RobustTools\Resala\Abstracts\Driver;
use RobustTools\Resala\Contracts\{SMSDriverInterface, SMSDriverResponseInterface};
use RobustTools\Resala\Response\GatewaySAResponse;
use RobustTools\Resala\Support\HTTP;

class GatewaySA extends Driver implements SMSDriverInterface
{
    private string $message;

    private string|array $recipients;

    private string $endPoint;

    public function __construct(private array $config)
    {
        $this->endPoint = $config['endpoint'];
    }

    /**
     * @param string|array $recipients
     * @return string|array
     */
    public function to($recipients): array|string
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
     * @return SMSDriverResponseInterface
     */
    public function send(): SMSDriverResponseInterface
    {
        $response = (new HTTP())->get($this->endPoint, $this->headers(), $this->payload());

        return new GatewaySAResponse($response);
    }

    /**
     * @return array
     */
    protected function payload(): array
    {
        return [
            'api_id' => $this->config['api_id'],
            'api_password' => $this->config['api_password'],
            'sender_id' => $this->config['sender_id'],
            'sms_type' => $this->config['sms_type'],
            'encoding' => $this->config['encoding'],
            'phonenumber' => $this->recipients,
            'textmessage' => $this->message,
        ];
    }

    protected function headers(): array
    {
        return ['Accept' => 'application/json'];
    }
}