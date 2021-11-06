<?php
namespace RobustTools\Resala\Response;

use Psr\Http\Message\ResponseInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;

final class ConnekioResponse implements SMSDriverResponseInterface
{
    private string $response;

    private int $statusCode;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response->getReasonPhrase();

        $this->statusCode = $response->getStatusCode();
    }

    public function success(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    public function body(): string
    {
        return $this->response;
    }
}
