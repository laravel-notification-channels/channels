<?php

namespace NotificationChannels\Unifonic\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static("Unifonic service responded with an error: {$response}'");
    }
}
