<?php

namespace NotificationChannels\WhatsApp\Test\Support;

use Illuminate\Notifications\Notification;
use NotificationChannels\WhatsApp\WhatsAppTemplate;

class DummyNotification extends Notification
{
    public function toWhatsApp($notifiable): WhatsAppTemplate
    {
        return WhatsAppTemplate::create()
            ->name('invoice_created')
            ->to('34678741298');
    }
}
