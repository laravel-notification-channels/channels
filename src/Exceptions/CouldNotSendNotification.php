<?php

namespace NotificationChannels\AwsSns\Exceptions;

use NotificationChannels\AwsSns\SnsMessage;

class CouldNotSendNotification extends \Exception
{
    /**
     * @return static
     */
    public static function invalidReceiver()
    {
        return new static(
            'The notifiable did not have a receiving phone number. Add a routeNotificationForSns
            method or one of the conventional attributes to your notifiable.'
        );
    }

    /**
     * @param mixed $message
     *
     * @return static
     */
    public static function invalidMessageObject($message)
    {
        $className = get_class($message) ?: 'Unknown';

        return new static(
            "Notification was not sent. Message object class `{$className}` is invalid. It should
            be a instance of `".SnsMessage::class.'`.'
        );
    }
}
