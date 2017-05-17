<?php

namespace NotificationChannels\Lox24\Exceptions;

class CouldNotSendNotification extends \Exception
{


    public static function lox24RespondedWithAnError(\Exception $exception)
    {
        return new static($exception->getMessage());
    }
}
