<?php

namespace NotificationChannels\Alidayu\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static(self::$message);
    }

    public static function serviceWithAnError(Exception $exception)
    {
        return new static("Communication with Discord failed: {$exception->getCode()}: {$exception->getMessage()}");
    }
}
