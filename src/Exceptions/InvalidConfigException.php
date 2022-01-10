<?php

namespace NotificationChannels\BulkGate\Exceptions;

class InvalidConfigException extends \Exception
{
    public static function invalidConfiguration($message): self
    {
        return new static($message);
    }

    public static function senderIdNotSet(): self
    {
        return new static('Sender ID is required for this type of sender. Set sender_id in configuration file.');
    }
}
