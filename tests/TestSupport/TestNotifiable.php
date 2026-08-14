<?php

namespace NotificationChannels\OneWaySms\Tests\TestSupport;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    /** @param  string|array<int, string>  $route */
    public function __construct(protected string|array $route = '60121234567')
    {
    }

    /** @return string|array<int, string> */
    public function routeNotificationForOneWaySms(): string|array
    {
        return $this->route;
    }
}
