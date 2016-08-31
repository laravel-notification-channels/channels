<?php

namespace NotificationChannels\Cmsms\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    /**
     * @param string $error
     * @return static
     */
    public static function serviceRespondedWithAnError(string $error)
    {
        return new static("CMSMS service responded with an error: {$error}'");
    }
}
