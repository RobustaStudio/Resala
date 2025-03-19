<?php

namespace RobustTools\Resala\Response;

use Psr\Http\Message\ResponseInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;

final class BrandEncodeResponse implements SMSDriverResponseInterface
{
    private const OK = 0;

    /**
     * @var array
     */
    private $response;

    /**
     * @var string
     */
    private string $statusCode;

    /**
     * @param  ResponseInterface  $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = json_decode($response->getBody()->getContents(), true);
        $this->statusCode = $response->getStatusCode();
    }

    /**
     * @inheritDoc
     */
    public function success(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300 && $this->response['code'] == self::OK;
    }

    /**
     * @inheritDoc
     */
    public function body(): string
    {
        return $this->response['message'];
    }
}
