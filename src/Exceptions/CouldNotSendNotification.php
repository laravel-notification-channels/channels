<?php

namespace NotificationChannels\Asana\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($message)
    {
        return new static("Asana responded with an error: `{$message}`");
    }
}
