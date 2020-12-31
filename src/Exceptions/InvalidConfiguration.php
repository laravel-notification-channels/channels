<?php

namespace NotificationChannels\Unifonic\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function configurationNotSet(): self
    {
        return new static('In order to send notifications via Unifonic you need to add credentials in the `unifonic` key of `config.services`.');
    }
}