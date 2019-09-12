<?php

namespace NotificationChannels\FortySixElks\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($message, $code)
    {
        return new static('46Elks responded with an error:'.$message.' '.$code);
    }
}
