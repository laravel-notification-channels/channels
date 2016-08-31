<?php

namespace NotificationChannels\Cmsms\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    /**
     * @return static
     */
    public static function configurationNotSet()
    {
        return new static('In order to send notification via CMSMS you need to add credentials in the `cmsms` key of `config.services`.');
    }
}
