<?php

namespace NotificationChannels\Hipchat\Exceptions;

use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends \Exception
{
    public static function hipchatRespondedWithAnError(ClientException $exception)
    {
        $code = $exception->getResponse()->getStatusCode();
        $message = $exception->getResponse()->getBody();

        return new static("Hipchat responded with an error `{$code} - {$message}`");
    }

    public static function missingTo()
    {
        return new static('Notification was not sent. Room identifier is missing.');
    }

    public static function invalidMessageObject($message)
    {
        $class = get_class($message) ?: 'Unknown';

        return new static("Notification was not sent. Message object class `{$class}` is invalid.");
    }

    public static function internalError()
    {
        return new static('Couldn\'t connect to Hipchat API.');
    }
}
