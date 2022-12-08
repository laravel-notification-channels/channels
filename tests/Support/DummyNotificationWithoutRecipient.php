<?php

namespace NotificationChannels\WhatsApp\Test\Support;

use Illuminate\Notifications\Notification;
use NotificationChannels\WhatsApp\WhatsAppTemplate;

class DummyNotificationWithoutRecipient extends Notification
{
    public function toWhatsApp($notifiable): WhatsAppTemplate
    {
        return WhatsAppTemplate::create()
            ->name('invoice_created');
    }
}
