<?php

namespace NotificationChannels\TelnectSms\Exceptions;

use Exception;

class InvalidConfigurationException extends Exception
{
    public static function configurationNotSet(): self
    {
        return new static('In order to send notifications via Telnect you need to add credentials in the `telnect_api` key of `config.services`.');
    }
}
