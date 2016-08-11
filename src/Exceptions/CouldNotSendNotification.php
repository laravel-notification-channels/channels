<?php

namespace NotificationChannels\CHANNEL_NAMESPACE\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError(array $response)
    {
        return new static("Descriptive error message.");
    }
}
