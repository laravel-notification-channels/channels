<?php

namespace NotificationChannels\Bonga\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($response): self
    {
        return new static("Error encountered: {$response}");
    }
}
