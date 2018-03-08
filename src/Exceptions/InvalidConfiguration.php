<?php

namespace NotificationChannels\Asana\Exceptions;

class InvalidConfiguration extends \Exception
{
    public static function configurationNotSet()
    {
        return new static('In order to send notification via Asana you need to add credentials config.asana and .env (see README).');
    }
}
