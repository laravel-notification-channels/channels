<?php

namespace NotificationChannels\Vodafone\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static("Code: $response->code - $response->reason.");
    }
}
