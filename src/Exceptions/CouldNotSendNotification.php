<?php

namespace NotificationChannels\SmsCentre\Exceptions;

use Exception;
use DomainException;

class CouldNotSendNotification extends Exception
{
    public static function serviceRespondedWithAnError(DomainException $exception)
    {
        return new static(
            "Service responded with an error '{$exception->getCode()}: {$exception->getMessage()}'"
        );
    }

    public static function serviceCommunicationError(Exception $exception)
    {
        return new static(
            "The communication with service failed because {$exception->getMessage()}"
        );
    }
}
