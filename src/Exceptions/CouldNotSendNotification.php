<?php

namespace NotificationChannels\Textlocal\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($exception)
    {
        return new static('Could Not Send SMS to TEXTLOCAL .'.$exception->getMessage());
    }
}
