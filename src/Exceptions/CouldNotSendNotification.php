<?php

namespace NotificationChannels\Notify\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when there's a bad response from Notify.
     *
     * @param ClientException $exception
     *
     * @return static
     */
    public static function serviceRespondedWithAnError(ClientException $exception)
    {
        $message = $exception->getResponse()->getBody();
        $code = $exception->getResponse()->getStatusCode();
        return new static("Notify responded with an error `{$code} - {$message}`");
    }
    /**
     * Thrown when we're unable to communicate with Notify.
     *
     * @param  Exception  $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithNotify(Exception $exception)
    {
        return new static("The communication with Notify failed. Reason: {$exception->getMessage()}");
    }
}