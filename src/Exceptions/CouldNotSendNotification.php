<?php

namespace NotificationChannels\TotalVoice\Exceptions;

use NotificationChannels\TotalVoice\TotalVoiceMessage;
use NotificationChannels\TotalVoice\TotalVoiceAudioMessage;

class CouldNotSendNotification extends \Exception
{
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
            be either `".TotalVoiceMessage::class.'` or `'.TotalVoiceAudioMessage::class.'`');
    }

    /**
     * @return static
     */
    public static function invalidReceiver()
    {
        return new static(
            'The notifiable did not have a receiving phone number. Add a routeNotificationForTotalVoice
            method or a phone_number attribute to your notifiable.');
    }
}
