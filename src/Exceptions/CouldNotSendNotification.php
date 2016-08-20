<?php

namespace NotificationChannels\Gammu\Exceptions;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when there is no phone Sender ID provided.
     *
     * @return static
     */
    public static function senderNotProvided()
    {
        return new static('Sender ID was not provided.');
    }

    /**
     * Thrown when there is no destination phone number provided.
     *
     * @return static
     */
    public static function destinationNotProvided()
    {
        return new static('Destination phone number was not provided.');
    }
}
