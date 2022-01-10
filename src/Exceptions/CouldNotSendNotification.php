<?php

namespace NotificationChannels\BulkGate\Exceptions;

use BulkGate\Message\Response;
use NotificationChannels\BulkGate\BulkGateMessage;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError(Response $response): self
    {
        return new static(
            'BulkGate responded with an error: `'.($response->error ?? 'undefined error').'`'
        );
    }

    public static function undefinedMethod(\Illuminate\Notifications\Notification $notification): self
    {
        return new static(
            'Notification of class: '.get_class($notification)
            .' must define a `toBulkGateSms()` method in order to send via the BulkGate SMS channel.'
        );
    }

    public static function invalidMessage($message): self
    {
        return new static(
            'Message must be an instance of '.BulkGateMessage::class.', '.gettype($message).' given.'
        );
    }

    public static function unexpectedException(\Exception $exception): self
    {
        return new static(
            'Failed to send SMS message, unexpected exception encountered: `'.$exception->getMessage().'`',
            0,
            $exception
        );
    }

    public static function invalidReceiver(): self
    {
        return new static(
            'The notifiable did not have a receiving phone number. Add a routeNotificationForBulkGateSms method
            to your notifiable model or a phone_number attribute to handle this.'
        );
    }
}
