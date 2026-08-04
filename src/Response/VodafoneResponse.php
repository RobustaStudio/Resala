<?php
namespace RobustTools\Resala\Response;

use Psr\Http\Message\ResponseInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;
use SimpleXMLElement;

final class VodafoneResponse implements SMSDriverResponseInterface
{
    private const SUBMIT_ERR = 'FAILED_TO_SUBMIT';

    private const TIME_OUT_ERR = 'TIMMED_OUT';

    private const BAD_REQUEST_ERR = 'INVALID_REQUEST';

    private const SERVER_ERR = 'INTERNAL_SERVER_ERROR';

    private const GENERIC_ERR = 'GENERIC_ERROR';

    private const SUBMITTED = 'SUBMITTED';

    private const OK = 'SUCCESS';

    private SimpleXMLElement $response;

    private string $resultStatus;

    private string $smsStatus;

    public function __construct(ResponseInterface $response)
    {
        $this->response = new SimpleXMLElement($response->getBody());
        $this->resultStatus = $this->response->ResultStatus;
        $this->smsStatus = $this->response->SMSStatus;
    }

    public function body(): string
    {
        return $this->response->Description;
    }

    public function success(): bool
    {
        return $this->resultStatus === self::OK
            && $this->smsStatus === self::SUBMITTED;
    }
}
