<?php

namespace NotificationChannels\Notify\Exceptions;

use Exception;

class InvalidMessageObject extends Exception
{
    /**
     * @return static
     */
    public static function missingNotificationType()
    {
        return new static('Notification was not sent. Missing `notificationType` param');
    }

    /**
     * @return static
     */
    public static function missingTransport()
    {
        return new static('Notification was not sent. Missing `transport` param');
    }

    /**
     * @return static
     */
    public static function missingRecipient()
    {
        return new static('Notification was not sent. Add a routeNotificationForNotify
            method to your notifiable.');
    }
}
