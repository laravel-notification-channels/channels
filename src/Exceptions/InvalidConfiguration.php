<?php

namespace NotificationChannels\Bonga\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function configurationNotSet(): self
    {
        return new static('To send notifications via Bonga SMS you need to add credentials in the `bonga` key of `config.services`.');
    }
}