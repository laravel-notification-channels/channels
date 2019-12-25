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

    public static function invalidResponse()
    {
        return new static('Invalid response from NetGSM server.');
    }

    public static function invalidMessageContent()
    {
        return new static('Invalid message content.');
    }

    public static function invalidHeader()
    {
        return new static('Invalid message header.');
    }

    public static function invalidRequest()
    {
        return new static('Invalid request.');
    }

    public static function unknownError()
    {
        return new static('Unknown Error.');
    }
}
