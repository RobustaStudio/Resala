<?php
namespace RobustTools\Resala\Support;

final class VodafoneResponseStatus
{
    private const SUBMIT_ERR = 'FAILED_TO_SUBMIT';

    private const TIME_OUT_ERR = 'TIMMED_OUT';

    private const BAD_REQUEST_ERR = 'INVALID_REQUEST';

    private const SERVER_ERR = 'INTERNAL_SERVER_ERROR';

    private const GENERIC_ERR = 'GENERIC_ERROR';

    private const SUBMITTED = 'SUBMITTED';

    private const OK = 'SUCCESS';

    public static function success(string $resultStatus, string $smsStatus): bool
    {
        return $resultStatus === self::OK
            && $smsStatus === self::SUBMITTED;
    }

    public static function clientErr(string $status): bool
    {
        return in_array($status, [self::SUBMIT_ERR, self::BAD_REQUEST_ERR, self::GENERIC_ERR, self::TIME_OUT_ERR]);
    }

    public static function serverErr(string $status): bool
    {
        return $status === self::SERVER_ERR;
    }
}
