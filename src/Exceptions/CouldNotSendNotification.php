<?php

namespace NotificationChannels\PushoverNotifications\Exceptions;

use Psr\Http\Message\ResponseInterface;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();

        if ($result = json_decode($response->getBody())) {
            if (isset($result->message)) {
                return new static('Pushover responded with an error ('.$statusCode.'): '.$result->message);
            }
        }

        return new static('Pushover responded with an error ('.$statusCode.').');
    }

    public static function serviceCommunicationError()
    {
        return new static("The communication with Pushover failed.");
    }
}
