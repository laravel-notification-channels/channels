<?php

namespace NotificationChannels\Chatwork\Exceptions;

class CouldNotSendNotification extends \Exception
{

    public static function serviceRespondedWithAnError($response)
    {
        $responseBody = print_r($response->errors[0]->message, true);
        return new static("Couldn't post Notification. Response: " . $responseBody);
    }
}
