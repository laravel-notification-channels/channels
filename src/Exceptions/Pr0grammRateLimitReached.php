<?php

namespace NotificationChannels\Pr0gramm\Exceptions;

use Spatie\Ignition\Contracts\BaseSolution;
use Spatie\Ignition\Contracts\ProvidesSolution;
use Spatie\Ignition\Contracts\Solution;

class Pr0grammRateLimitReached extends \Exception
{
    public static function rateLimitReached(): static
    {
        return new static("Rate limit reached. Please wait a few seconds before sending another message.");
    }
}
