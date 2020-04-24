<?php

namespace NotificationChannels\SMS77\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($message)
    {
        return new static('SMS77 Response: '.$message);
    }

    public static function apiKeyNotProvided(): self
    {
        return new static('API key is missing.');
    }

    public static function serviceNotAvailable($message): self
    {
        return new static($message);
    }

    public static function phoneNumberNotProvided(): self
    {
        return new static('No phone number was provided.');
    }
}
