<?php

namespace RobustTools\Resala\Response;

use Psr\Http\Message\ResponseInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;
use RobustTools\Resala\Support\VodafoneResponseStatus;
use SimpleXMLElement;

final class VodafoneDriverResponse implements SMSDriverResponseInterface
{
    private SimpleXMLElement $response;

    private string $resultStatus;

    private string $smsStatus;

    public function __construct (ResponseInterface $response)
    {
        $this->response = new SimpleXMLElement($response->getBody());
        $this->resultStatus = $this->response->ResultStatus;
        $this->smsStatus = $this->response->SMSStatus;
    }

    public function ok (): bool
    {
        return VodafoneResponseStatus::success($this->resultStatus, $this->smsStatus);
    }

    public function serverError (): bool
    {
        return VodafoneResponseStatus::serverErr($this->resultStatus);
    }

    public function clientError (): bool
    {
        return VodafoneResponseStatus::clientErr($this->resultStatus);
    }

    public function body (): string
    {
        return $this->response->Description;
    }
}
