<?php

namespace NotificationChannels\Zendesk\Exceptions;

class InvalidConfiguration extends \Exception
{
    public static function configurationNotSet()
    {
        return new static('In order to send notification via Zendesk you need to add credentials in the `zendesk` key of `config.services`.');
    }
}
