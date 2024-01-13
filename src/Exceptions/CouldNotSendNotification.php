<?php

namespace NotificationChannels\Pr0gramm\Exceptions;

use Spatie\Ignition\Contracts\BaseSolution;
use Spatie\Ignition\Contracts\ProvidesSolution;
use Spatie\Ignition\Contracts\Solution;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($body): static
    {
        return new static("Descriptive error message. " . $body);
    }

    public static function couldNotFindToPr0grammMethod(): static
    {
        return new static("Could not find toPr0gramm method on the notification.");
    }

    public static function couldNotFindGetPr0grammNameMethod(): static
    {
        return new static("Could not find getPr0grammName method on the notifiable object.");
    }

    public static function noStringForMessageProvided(): static
    {
        return new static("No string for message provided. Please provide a string in the toPr0gramm Method.");
    }

    public static function rateLimitReached(): static
    {
        return new static("Rate limit reached. Please wait a few seconds before sending another message.");
    }
}
