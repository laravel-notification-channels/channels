<?php

namespace NotificationChannels\AllMySms\Test;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForAllMySms(): string
    {
        return '0602030405';
    }
}
