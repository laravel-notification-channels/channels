<?php

namespace NotificationChannels\RealtimePushNotifications\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static('Realtime responded with an error');
    }
}
