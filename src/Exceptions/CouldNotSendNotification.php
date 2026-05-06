<?php

namespace NotificationChannels\TelnectSms\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError(string $error): CouldNotSendNotification
    {
        return new static("Telnect responded with an error: {$error}");
    }
}
