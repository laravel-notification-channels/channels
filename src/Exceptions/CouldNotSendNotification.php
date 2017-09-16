<?php

namespace NotificationChannels\TurboSms\Exceptions;

class CouldNotSendNotification extends \Exception
{
    /**
     * @param string $message
     *
     * @return static
     */
    public static function serviceRespondedWithAnError(string $message)
    {
        return new static('TurboSMS responded with an error "{$message}"');
    }
}
