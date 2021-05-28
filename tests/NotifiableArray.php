<?php

namespace NotificationChannels\Expo\Test;

class NotifiableArray
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return array
     */
    public function routeNotificationForExpo()
    {
        return ['ExponentPushToken[yyyyyyyyyyyyyyyyyyyyyy]', 'ExponentPushToken[zzzzzzzzzzzzzzzzzzzzzz]'];
    }
}
