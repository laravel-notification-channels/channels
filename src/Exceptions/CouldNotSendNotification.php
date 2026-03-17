<?php

namespace NotificationChannels\KwtSms\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithError(string $code, string $description): static
    {
        return new static("kwtSMS API error [{$code}]: {$description}");
    }
}
