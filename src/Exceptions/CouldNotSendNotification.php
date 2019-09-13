<?php

namespace NotificationChannels\Textlocal\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($exception, $message = null)
    {
        return new static('Could Not Send SMS : '.$exception->getMessage() . ' message: ' . $message);
    }
}
