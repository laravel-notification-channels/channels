<?php

namespace NotificationChannels\TurboSms\Exceptions;

class CouldNotSendNotification extends \Exception
{
    /**
     * @param string $message
     *
     * @return static
     */
    public static function serviceRespondedWithAnError(string $message)
    {
        return new static( 'TurboSMS responded with an error "{$message}"', 424 );
    }
    
    /**
     * @return static
     */
    public static function SenderRequired()
    {
        return new static( 'Notification was not sent. Sender must be defined.', 400 );
    }
    
    /**
     * @return static
     */
    public static function RecipientRequired()
    {
        return new static( 'Notification was not sent. Recipient must be defined.', 400 );
    }
    
    /**
     * @return static
     */
    public static function MessageRequired()
    {
        return new static( 'Notification was not sent. Message must be defined.', 400 );
    }
}
