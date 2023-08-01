<?php

namespace NotificationChannels\KChat\Exceptions;

use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends \Exception
{
    public static function kChatRespondedWithAnError(ClientException $exception)
    {
        if (! $exception->hasResponse()) {
            return new static('kChat responded with an error but no response body found');
        }

        $statusCode = $exception->getResponse()->getStatusCode();
        $description = $exception->getMessage();

        return new static("kChat responded with an error `{$statusCode} - {$description}`");
    }

    public static function couldNotCommunicateWithkChat(\Exception $exception)
    {
        return new static("The communication with kChat failed. `{$exception->getMessage()}`");
    }

    public static function baseUrlMissing()
    {
        return new static('The base Url of your kChat instance is missing. Please add it in your config/services.php file.');
    }

    public static function channelMissing()
    {
        return new static('The channel ID you wish to send the notification to is missing.');
    }

    public static function messageMissing()
    {
        return new static('The message is missing.');
    }
}
