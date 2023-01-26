<?php

namespace NotificationChannels\ClickSend\Facades;

use ClickSend\Api\SMSApi as Client;
use Illuminate\Support\Facades\Facade;

class ClickSend extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return Client::class;
    }
}
