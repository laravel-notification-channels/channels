<?php

namespace NotificationChannels\Webex\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class CouldNotSendNotification extends Exception
{
    public static function webexRespondedWithClientError(ClientException $exception): CouldNotSendNotification
    {
        $code = $exception->getCode();

        return new static("Webex responded with client error $code.", $code, $exception);
    }

    public static function webexRespondedWithServerError(ServerException $exception): CouldNotSendNotification
    {
        $code = $exception->getCode();

        return new static("Webex responded with server error $code.", $code, $exception);
    }

    public static function communicationError(Exception $exception): CouldNotSendNotification
    {
        return new static('Could not communicate with Webex.', $exception->getCode(), $exception);
    }

    public static function missingConfiguration(): CouldNotSendNotification
    {
        return new static('Please ensure that Webex service url, id, and token are set.');
    }
}
