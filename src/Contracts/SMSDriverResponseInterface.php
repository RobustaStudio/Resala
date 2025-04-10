<?php

namespace RobustTools\Resala\Contracts;

use Psr\Http\Message\ResponseInterface;

interface SMSDriverResponseInterface
{
    public function __construct(ResponseInterface $response);

    public function success(): bool;

    public function body(): string;
}
