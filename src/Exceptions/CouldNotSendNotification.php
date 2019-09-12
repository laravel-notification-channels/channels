<?php

namespace NotificationChannels\NetGsm\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError(\Exception $exception)
    {
        return new static("NetGsm service responded with an error '{$exception->getCode()}: {$exception->getMessage()}'");
    }

    public static function emptyRecipients()
    {
        return new static('In order to send notification via NetGsm you need to add some recipients.');
    }
}
