<?php

namespace NotificationChannels\TransmitMessage\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        echo $response->getMessage();
        return new static($response->getMessage());
    }
}
