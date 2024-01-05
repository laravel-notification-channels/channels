<?php

namespace NotificationChannels\Ntfy\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static($response);
    }

    public static function missingNtfyMethod()
    {
        return new static('Notification is missing toNtfy() method.');
    }
}
