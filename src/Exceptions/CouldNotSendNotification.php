<?php

namespace NotificationChannels\PusherApiNotifications\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        $encodedResponse = is_string($response) || is_bool($response)
            ? $response : json_encode($response);

        return new static("Message could not be sent: $encodedResponse");
    }
}
