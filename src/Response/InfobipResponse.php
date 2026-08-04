<?php
namespace RobustTools\Resala\Response;

use Psr\Http\Message\ResponseInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;

final class InfobipResponse implements SMSDriverResponseInterface
{
    private array $response;

    private int $statusCode;

    public function __construct(ResponseInterface $response)
    {
        $this->response = json_decode($response->getBody()->getContents(), true);
        $this->statusCode = $response->getStatusCode();
    }

    public function body(): string
    {
        if ($this->success()) {
            $message = $this->response['messages'][0];

            return $message['status']['name'] . ": " . $message['status']['description'];
        } else {
            $error = $this->response['requestError']['serviceException'];

            return $error['messageId'] . ": " . $error['text'];
        }
    }

    public function success(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }
}
