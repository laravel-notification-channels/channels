<?php

namespace NotificationChannels\Signal\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError(string $response)
    {
        return sprintf("Unable to send message. Please ensure recipient is registered with Signal. Symfony output was: `{$response}['$result']`");
    }
}
