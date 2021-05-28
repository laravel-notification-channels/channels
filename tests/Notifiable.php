<?php

namespace NotificationChannels\Expo\Test;

class Notifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForExpo()
    {
        return 'ExponentPushToken[xxxxxxxxxxxxxxxxxxxxxx]';
    }
}
