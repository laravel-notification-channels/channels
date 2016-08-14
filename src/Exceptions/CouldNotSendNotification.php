<?php

namespace NotificationChannels\Clickatell\Exceptions;

class CouldNotSendNotification extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @return static
     */
    public static function serviceRespondedWithAnError($message, $code)
    {
        return new static(
            "Clickatell responded with an error '{$message}: {$code}'"
        );
    }
}
