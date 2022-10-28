<?php

namespace NotificationChannels\MstatGr\Exceptions;

class InsufficientBalance extends \Exception
{
    public static function serviceRespondedWithAnError()
    {
        return new static("Insufficient balance.");
    }
}
