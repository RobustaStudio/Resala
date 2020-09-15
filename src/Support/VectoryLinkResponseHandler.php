<?php

namespace RobustTools\SMS\Support;

use SimpleXMLElement;

class VectoryLinkResponseHandler
{
    public static function respond(SimpleXMLElement $response): string
    {
        switch ((int) $response) {
            case 0:
                return 'Message Sent Succesfully';
                break;

            case -1:
                return 'User is not subscribed';
                break;

            case -5:
                return 'Out of credit.';
                break;

            case -10:
                return 'Queued Message, no need to send it again.';
                break;

            case -11:
                return 'Invalid language.';
                break;

            case -12:
                return 'SMS is empty.';
                break;

            case -13:
                return 'Invalid fake sender exceeded 12 chars or empty.';
                break;

            case -25:
                return 'Sending rate greater than receiving rate (only for send/receive accounts).';
                break;

            case -100:
                return 'Other error';
                break;

            default:
                return 'Unknown Error Code';
                break;
        }
    }
}
