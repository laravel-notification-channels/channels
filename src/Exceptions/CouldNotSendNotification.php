<?php

namespace NotificationChannels\Signal\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function serviceRespondedWithAnError(string $response)
    {
      $format = 'Unable to send message. Please ensure recipient is registered with Signal. Symfony output was: %s';
      return new sprintf($format, $response);
    }
}
