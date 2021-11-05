<?php
namespace RobustTools\Resala\Contracts;

use Psr\Http\Message\ResponseInterface;

interface SMSDriverResponseInterface
{
    public function __construct (ResponseInterface $response);

    public function ok (): bool;

    public function serverError (): bool;

    public function clientError (): bool;

    public function body (): string;
}
