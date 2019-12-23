<?php

namespace NotificationChannels\Vodafone\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function methodDoesNotExist()
    {
        return new static(`The toVodafone method does not exist in your notification class.`);
    }

    public static function incorrectMessageClass()
    {
        return new static(`Your notification is incorrectly formatted or needs to use an instance of the VodafoneMessage class.`);
    }

    public static function serviceUnknownResponse()
    {
        return new static(`Unknown response coming from the Vodafone API.`);
    }

    public static function serviceRespondedWithAnError($response)
    {
        return new static(`Code: $response->code - $response->reason.`);
    }
}
