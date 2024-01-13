<?php

namespace NotificationChannels\Pr0gramm\Exceptions;

use Exception;

class Pr0grammRateLimitReached extends Exception
{
    public static function rateLimitReached(): static
    {
        return new static("Rate limit reached. Please wait a few seconds before sending another message.");
    }
}
