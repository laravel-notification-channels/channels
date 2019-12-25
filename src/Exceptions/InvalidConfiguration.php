<?php

namespace NotificationChannels\NetGsm\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    /**
     * @return static
     */
    public static function configurationNotSet()
    {
        return new static('In order to send notification via NetGsm you need to add credentials in the `netgsm` key of `config.services`.');
    }

    /**
     * @return static
     */
    public static function invalidCredentials()
    {
        return new static('Invalid API credentials');
    }
}
