<?php

namespace NotificationChannels\Cmsms\Exceptions;

use Exception;

class InvalidMessage extends Exception
{
    /**
     * @param string $reference
     * @return static
     */
    public static function invalidReference($reference)
    {
        return new static("The reference on the CMSMS message may only contain 1 - 32 alphanumeric characters. Was given '{$reference}'");
    }
}
