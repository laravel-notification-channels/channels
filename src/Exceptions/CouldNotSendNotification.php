<?php

namespace NotificationChannels\ClickSend\Exceptions;

use ClickSend\ApiException;

class CouldNotSendNotification extends \Exception
{
    public static function ClickSendApiException(ApiException $exception): self
    {
        return new static(
            "ClickSend could not send and responded with an error.", null, $exception
        );
    }
}
