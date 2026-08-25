<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Test\Feature;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\RuStore\RuStoreChannel;
use NotificationChannels\RuStore\Test\Notifications\TestNotification;
use NotificationChannels\RuStore\Test\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * NotificationTest - проверка отправки уведомлений.
 */
class NotificationTest extends TestCase
{
    #[Test]
    #[TestDox('Простая проверка отправки уведомления через канал RuStoreChannel')]
    public function notificationSimple(): void
    {
        Notification::fake();
        $notification = new TestNotification();
        $notifiable = new AnonymousNotifiable();

        $notifiable->notify($notification);

        Notification::assertSentTo(
            $notifiable,
            TestNotification::class,
            static function ($notification, $channels) {
                return in_array(RuStoreChannel::class, $channels, true);
            }
        );
    }
}
