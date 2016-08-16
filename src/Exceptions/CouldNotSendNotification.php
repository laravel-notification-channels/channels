<?php

namespace NotificationChannels\Gitter\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when room identifier is missing.
     *
     * @return static
     */
    public static function missingRoom()
    {
        return new static('Notification was not sent. Room identifier is missing.');
    }

    /**
     * Thrown when user or app access token is missing.
     *
     * @return static
     */
    public static function missingFrom()
    {
        return new static('Notification was not sent. Access token is missing.');
    }

    /**
     * Thrown when there's a bad response from the Gitter.
     *
     * @param ClientException $exception
     *
     * @return static
     */
    public static function gitterRespondedWithAnError(ClientException $exception)
    {
        $message = $exception->getResponse()->getBody();
        $code = $exception->getResponse()->getStatusCode();

        return new static("Gitter responded with an error `{$code} - {$message}`");
    }

    /**
     * Thrown when we're unable to communicate with Gitter.
     *
     * @param  Exception  $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithGitter(Exception $exception)
    {
        return new static('The communication with Gitter failed. Reason: '.$exception->getMessage());
    }
}
