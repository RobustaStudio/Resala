<?php
namespace RobustTools\Resala\Response;

use Psr\Http\Message\ResponseInterface;
use RobustTools\Resala\Contracts\SMSDriverResponseInterface;

final class VectoryLinkResponse implements SMSDriverResponseInterface
{
    private const STATUS_SUCCESS = 0;
    private const STATUS_INVALID_CREDENTIALS = -1;
    private const STATUS_INVALID_ACCOUNT_IP = -2;
    private const STATUS_INVALID_ANI_BLACK_LIST = -3;
    private const STATUS_OUT_OF_CREDIT = -5;
    private const STATUS_DATABASE_DOWN = -6;
    private const STATUS_INACTIVE_ACCOUNT = -7;
    private const STATUS_ACCOUNT_EXPIRED = -11;
    private const STATUS_SMS_EMPTY = -12;
    private const STATUS_INVALID_SENDER_WITH_CONNECTION = -13;
    private const STATUS_SMS_SENDING_FAILED = -14;
    private const STATUS_OTHER_ERROR = -100;
    private const STATUS_INVALID_ANI = -18;
    private const STATUS_SMS_ID_EXISTS = -19;
    private const STATUS_INVALID_ACCOUNT = -21;
    private const STATUS_SMS_NOT_VALIDATE = -22;
    private const STATUS_INVALID_ACCOUNT_OPERATOR_CONNECTION = -23;
    private const STATUS_INVALID_USER_SMS_ID = -26;
    private const STATUS_EMPTY_USERNAME_OR_PASSWORD = -29;
    private const STATUS_INVALID_SENDER = -30;

    private int $status;

    public function __construct(ResponseInterface $response)
    {
        $contents = trim($response->getBody()->getContents());
        $this->status = $this->parseStatus($contents);
    }

    public function success(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function body(): string
    {
        $messages = [
            self::STATUS_SUCCESS => 'Success',
            self::STATUS_INVALID_CREDENTIALS => 'Invalid Credentials',
            self::STATUS_INVALID_ACCOUNT_IP => 'Invalid Account IP',
            self::STATUS_INVALID_ANI_BLACK_LIST => 'Invalid ANI Black List',
            self::STATUS_OUT_OF_CREDIT => 'Out Of Credit',
            self::STATUS_DATABASE_DOWN => 'Database Down',
            self::STATUS_INACTIVE_ACCOUNT => 'Inactive Account',
            self::STATUS_ACCOUNT_EXPIRED => 'Account Is Expired',
            self::STATUS_SMS_EMPTY => 'SMS Is Empty',
            self::STATUS_INVALID_SENDER_WITH_CONNECTION => 'Invalid Sender With Connection',
            self::STATUS_SMS_SENDING_FAILED => 'SMS Sending Failed Try Again',
            self::STATUS_OTHER_ERROR => 'Other Error',
            self::STATUS_INVALID_ANI => 'Invalid ANI',
            self::STATUS_SMS_ID_EXISTS => 'SMS Id Is Exist',
            self::STATUS_INVALID_ACCOUNT => 'Invalid Account',
            self::STATUS_SMS_NOT_VALIDATE => 'SMS Not Validate',
            self::STATUS_INVALID_ACCOUNT_OPERATOR_CONNECTION => 'Invalid Account Operator Connection',
            self::STATUS_INVALID_USER_SMS_ID => 'Invalid User SMS Id',
            self::STATUS_EMPTY_USERNAME_OR_PASSWORD => 'Empty User Name Or Password',
            self::STATUS_INVALID_SENDER => 'Invalid Sender',
        ];

        return $messages[$this->status] ?? 'Something wrong happened';
    }

    private function parseStatus(string $contents): int
    {
        if ($contents === '' || !is_numeric($contents)) {
            return self::STATUS_OTHER_ERROR;
        }

        return (int) $contents;
    }
}
