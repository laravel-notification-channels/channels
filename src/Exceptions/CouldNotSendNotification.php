<?php

namespace NotificationChannels\Onewaysms\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when content length is greater than 459 characters.
     *
     * @param $count
     * @return static
     */
    public static function contentLengthLimitExceeded($count): self
    {
        return new static("Notification was not sent. Content length may not be greater than {$count} characters.", 422);
    }

    /**
     * Thrown when we're unable to communicate with Onewaysms.
     *
     * @param ClientException $exception
     *
     * @return static
     */
    public static function OnewaysmsRespondedWithAnError(ClientException $exception): self
    {
        if (! $exception->hasResponse()) {
            return new static('OneWaySMS responded with an error but no response body found');
        }

        return new static("OneWaySMS responded with an error '{$exception->getCode()} : {$exception->getMessage()}'", $exception->getCode(), $exception);
    }

    /**
     * Thrown when we're unable to communicate with Onewaysms.
     *
     * @param Exception $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithOnewaysms(Exception $exception): self
    {
        return new static("The communication with OneWaySMS failed. Reason: {$exception->getMessage()}", $exception->getCode(), $exception);
    }
}