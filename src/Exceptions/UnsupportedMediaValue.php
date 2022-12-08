<?php

namespace NotificationChannels\WhatsApp\Exceptions;

class UnsupportedMediaValue extends \Exception
{
    public function __construct($value, string $mediaType, string $extendedMessage = '')
    {
        $message = "The $value value for $mediaType is unsupported.";
        $message .= $extendedMessage ? " Message: $extendedMessage" : '';

        parent::__construct($message);
    }
}
