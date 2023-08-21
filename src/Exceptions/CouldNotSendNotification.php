<?php

namespace NotificationChannels\LaravelZenviaChannel\Exceptions;

use NotificationChannels\LaravelZenviaChannel\ZenviaMessage;
use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends \Exception
{
    public static function invalidMessageObject($message): self
    {
        $className = is_object($message) ? get_class($message) : 'Unknown';

        return new static(
            "Notification was not sent. Message object class `{$className}` is invalid. It should
            be either `".ZenviaMessage::class.'`');
    }

    public static function invalidReceiver(): self
    {
        return new static(
            'The notifiable did not have a receiving phone number. Add a routeNotificationForZenvia method or a phone_number attribute to your notifiable.'
        );
    }

    public static function contentNotProvided(): self
    {
        return new static(
            'Sms content not provided'
        );
    }

    public static function serviceConnectionError(): self
    {
        return new static(
            'Could not connect with Zenvia Service'
        );
    }

    /**
     * Thrown when there's a bad request and an error is responded.
     *
     * @param ClientException $exception
     *
     * @return static
     */
    public static function serviceRespondedWithAnError(ClientException $exception): self
    {
        $statusCode  = $exception->getResponse()->getStatusCode();
        $description = 'no description given';

        if ($result = json_decode($exception->getResponse()->getBody())) {
            $description = $result->description ?: $description;
        }

        return new static(
            "Zenvia responded with an error `{$statusCode} - {$description}`"
        );
    }
}
