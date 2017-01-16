<?php

namespace NotificationChannels\Zendesk\Exceptions;

class CouldNotCreateMessage extends \Exception
{
    public static function invalidType($type)
    {
        return new static("Ticket type `{$type}` is invalid. Allowed values are problem, incident, question, or task.");
    }

    public static function invalidPriority($priority)
    {
        return new static("Ticket priority `{$priority}` is invalid. It should be urgent, high, normal, or low.");
    }
}
