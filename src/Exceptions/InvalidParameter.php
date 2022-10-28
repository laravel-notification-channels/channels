<?php

namespace NotificationChannels\MstatGr\Exceptions;

class InvalidParameter extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static($response);
    }
}
