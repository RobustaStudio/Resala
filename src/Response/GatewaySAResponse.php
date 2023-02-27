<?php
namespace RobustTools\Resala\Response;

use Psr\Http\Message\ResponseInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;

final class GatewaySAResponse implements SMSDriverResponseInterface
{
    private array $response;

    private string $statusCode;

    public function __construct(ResponseInterface $response)
    {
        $this->response = json_decode($response->getBody()->getContents(), true);

        $this->statusCode = $this->response['status'];
    }

    public function body(): string
    {
        return $this->response['remarks'] ?? "";
    }

    public function success(): bool
    {
        return $this->statusCode == 'S';
    }
}
