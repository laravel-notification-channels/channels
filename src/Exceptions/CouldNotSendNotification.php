<?php

namespace NotificationChannels\WhatsApp\Exceptions;

final class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError($responseBody)
    {
        return new self($responseBody);
    }
}
