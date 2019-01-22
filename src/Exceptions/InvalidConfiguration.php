<?php

namespace NotificationChannels\Pushmix\Exceptions;

class InvalidConfiguration extends \Exception
{
    public static function configurationNotSet()
    {
        return new static('Invalid credentials: `services.php` missing   `pushmix => ["key" => "SUBSCRIPTION_ID" ]`');
    }
}
