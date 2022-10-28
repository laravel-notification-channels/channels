<?php

namespace NotificationChannels\MstatGr\Exceptions;

class AuthKeyIsMissing extends \Exception
{
    public static function serviceRespondedWithAnError()
    {
        return new static("Auth Key is empty.");
    }
}
