<?php

namespace NotificationChannels\Zendesk\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($message)
    {
        return new static('Zendesk responded with an error: `'.$message);
    }
}
