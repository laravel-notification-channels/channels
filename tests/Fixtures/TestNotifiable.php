<?php

namespace NotificationChannels\GoogleChat\Tests\Fixtures;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    private $space;

    public function __construct(string $space = null)
    {
        $this->space = $space;
    }

    public function routeNotificationForGoogleChat()
    {
        return $this->space;
    }
}
