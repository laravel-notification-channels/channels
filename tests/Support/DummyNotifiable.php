<?php

namespace NotificationChannels\WhatsApp\Test\Support;

class DummyNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForWhatsApp()
    {
        return '0123456789';
    }
}
