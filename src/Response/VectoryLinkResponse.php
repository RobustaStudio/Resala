<?php
namespace RobustTools\Resala\Response;

use Psr\Http\Message\ResponseInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;
use SimpleXMLElement;

final class VectoryLinkResponse implements SMSDriverResponseInterface
{
    private const OK = 0;

    private const USER_ERR = -1;

    private const CREDIT_ERR = -5;

    private const OK_QUEUED = -10;

    private const LANG_ERR = -11;

    private const SMS_ERR = -12;

    private const SENDER_ERR = -13;

    private const SENDING_RATE_ERR = -25;

    private const GENERIC_ERR = -100;

    private SimpleXMLElement $response;

    private int $status;

    public function __construct(ResponseInterface $response)
    {
        $this->response = new SimpleXMLElement($response->getBody());
        $this->status = (int) current(get_mangled_object_vars($this->response));
    }

    public function success(): bool
    {
        return $this->status === self::OK
            || $this->status === self::OK_QUEUED;
    }

    public function body(): string
    {
        return [
            self::OK => 'Message Sent Successfully',
            self::USER_ERR => 'User is not subscribed',
            self::CREDIT_ERR => 'Out of credit.',
            self::OK_QUEUED => 'Queued Message, no need to send it again.',
            self::LANG_ERR => 'Invalid language.',
            self::SMS_ERR => 'SMS is empty.',
            self::SENDER_ERR => 'Invalid fake sender exceeded 12 chars or empty.',
            self::SENDING_RATE_ERR => 'Sending rate greater than receiving rate (only for send/receive accounts).',
            self::GENERIC_ERR => 'Other error',
        ][$this->status] ?? 'Something wrong happened';
    }
}
