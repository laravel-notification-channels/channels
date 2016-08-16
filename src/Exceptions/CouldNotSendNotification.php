<?php

namespace NotificationChannels\Discord\Exceptions;

use Exception;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;

class CouldNotSendNotification extends Exception
{
    /**
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @return static
     */
    public static function serviceRespondedWithAnHttpError(ResponseInterface $response)
    {
        $message = "Discord responded with an HTTP error: {$response->getStatusCode()}";

        if ($error = Arr::get(json_decode($response->getBody(), true), 'message')) {
            $message .= ": $error";
        }

        return new static($message);
    }

    /**
     * @param  array  $response
     * @return static
     */
    public static function serviceRespondedWithAnApiError($response)
    {
        return new static("Discord responded with an API error: {$response['code']}: {$response['message']}");
    }

    /**
     * @param  \Exception  $e
     * @return static
     */
    public static function serviceCommunicationError(Exception $e)
    {
        return new static("Communication with Discord failed: {$e->getCode()}: {$e->getMessage()}");
    }
}
