<?php

namespace NotificationChannels\MoceanApi\Exceptions;

use Mocean\Message\Message;
use NotificationChannels\MoceanApi\MoceanApiSmsMessage;

class CouldNotSendNotification extends \Exception
{
    /**
     * @return static
     */
    public static function invalidReceiver()
    {
        return new static('invalid receiver, please add routeNotificationForMoceanapi method to your notifiable');
    }

    /**
     * @param mixed $message
     * @return static
     */
    public static function invalidMessageObject($message)
    {
        $className = get_class($message) ?: 'Unknown';

        return new static("Invalid message object `{$className}`, it should be `" . MoceanApiSmsMessage::class . '` or `' . Message::class . '`');
    }
}
