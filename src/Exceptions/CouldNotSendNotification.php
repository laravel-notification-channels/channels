<?php

namespace NotificationChannels\Signal\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static("Unable to send message. Please ensure recipient is registered with Signal. Symfony output was: `{$response}['$symfonyerror']`");
    }
}
