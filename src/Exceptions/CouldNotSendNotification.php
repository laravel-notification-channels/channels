<?php

namespace NotificationChannels\Pubnub\Exceptions;

use Exception;
use Pubnub\PubnubException;

class CouldNotSendNotification extends Exception
{
    public static function pubnubRespondedWithAnError(PubnubException $exception)
    {
        return new static($exception->getMessage());
    }

    public static function missingChannel()
    {
        return new static('Notification not sent. Missing channel');
    }
}
