<?php

namespace NotificationChannels\AllMySms\Exceptions;

use Psr\Http\Message\ResponseInterface;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError(ResponseInterface $response)
    {
        return new static('AllMySms responded with an error: `'.$response->getReasonPhrase().'`');
    }
}
