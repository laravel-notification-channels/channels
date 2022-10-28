<?php

namespace NotificationChannels\MstatGr\Exceptions;

class AuthKeyIsInvalid extends \Exception
{
    public static function serviceRespondedWithAnError()
    {
        return new static('Auth Key is invalid.');
    }
}
