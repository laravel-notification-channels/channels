<?php

namespace NotificationChannels\FortySixElks\Exceptions;

class MissingConfigNotification extends \Exception
{
    public static function missingConfig()
    {
        return new static('46 elks username and / or password are missing. Did you add it to service array and check your .env file?');
    }
}
