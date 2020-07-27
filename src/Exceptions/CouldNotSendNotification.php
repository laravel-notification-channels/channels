<?php

namespace NotificationChannels\Infobip\Exceptions;

use NotificationChannels\Infobip\InfobipMessage;
use NotificationChannels\Infobip\InfobipSmsAdvancedMessage;

class CouldNotSendNotification extends \Exception
{
    /**
     * @return static
     */
    public static function invalidCredentials()
    {
        return new static('Invalid credentials');
    }

    /**
     * @return static
     */
    public static function invalidReceiver()
    {
        return new static('The notifiable did not have a phone number. Add routeNotificationForInfoBip to your notifiable');
    }

    /**
     * @return static
     */
    public static function missingFrom()
    {
        return new static('Notification was not sent. Missing `from` number.');
    }

    /**
     * @return static
     */
    public static function missingNotifyUrl()
    {
        return new static('Notification was not sent. Missing `notify_url`');
    }

    /**
     * @param $message
     * @return static
     */
    public static function invalidMessageObject($message)
    {
        $className = get_class($message) ?: 'Unknown';

        return new static(
            'Notification was not sent. Message object class '.$className.
            ' is invalid. It should be either '.InfobipMessage::class.
            ' or '.InfobipSmsAdvancedMessage::class
        );
    }
}
