<?php

namespace NotificationChannels\MstatGr\Exceptions;

class IpAddressInvalid extends \Exception
{
    public static function serviceRespondedWithAnError()
    {
        return new static('IP Address is invalid.');
    }
}
