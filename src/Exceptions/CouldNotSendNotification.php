<?php

namespace NotificationChannels\Smspoh\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when content length is greater than 160 characters.
     *
     * @return static
     */
    public static function contentLengthLimitExceeded($count): self
    {
        return new static("Notification was not sent. Content length may not be greater than {$count} characters.", 422);
    }

    /**
     * Thrown when we're unable to communicate with smspoh.
     *
     * @param ClientException $exception
     *
     * @return static
     */
    public static function smspohRespondedWithAnError(ClientException $exception): self
    {
        if (! $exception->hasResponse()) {
            return new static('Smspoh responded with an error but no response body found');
        }

        return new static("Smspoh responded with an error '{$exception->getCode()} : {$exception->getMessage()}'", $exception->getCode(), $exception);
    }

    /**
     * Thrown when we're unable to communicate with smspoh.
     *
     * @param Exception $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithSmspoh(Exception $exception): self
    {
        return new static("The communication with smspoh failed. Reason: {$exception->getMessage()}", $exception->getCode(), $exception);
    }
}
