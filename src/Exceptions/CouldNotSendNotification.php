<?php

namespace NotificationChannels\PusherApiNotifications\Exceptions;

use function GuzzleHttp\json_encode;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        $encodedResponse = json_encode($response);
        return new static("Message could not be sent: $encodedResponse");
    }
}
