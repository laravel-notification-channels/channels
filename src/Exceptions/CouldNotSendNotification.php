<?php

namespace NotificationChannels\Workplace\Exceptions;

use GuzzleHttp\Exception\ClientException;

class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when there's a bad request and an error is responded.
     *
     * @param ClientException $exception
     *
     * @return static
     */
    public static function workplaceRespondedWithAnError(ClientException $exception)
    {
        $statusCode = $exception->getResponse()->getStatusCode();
        $description = 'No description given';
        if ($result = json_decode($exception->getResponse()->getBody())) {
            $description = $result->description ?? $description;
        }

        return new static("Workplace responded with an error `{$statusCode} - {$description}`");
    }

    /**
     * Thrown when there is workplace endpoint defined.
     *
     * @return static
     */
    public static function endpointNotProvided()
    {
        return new static('Workplace notification endpoint was not provided. Please refer usage docs.');
    }

    /**
     * Thrown when we're unable to communicate with Workplace.
     *
     * @return static
     */
    public static function couldNotCommunicateWithWorkplace($message)
    {
        return new static("The communication with Workplace failed. `{$message}`");
    }
}
