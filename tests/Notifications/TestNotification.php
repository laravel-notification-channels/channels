<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore\Test\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\RuStore\Resources\MessageAndroid;
use NotificationChannels\RuStore\Resources\MessageAndroidNotification;
use NotificationChannels\RuStore\Resources\MessageNotification;
use NotificationChannels\RuStore\RuStoreChannel;
use NotificationChannels\RuStore\RuStoreMessage;

class TestNotification extends Notification
{
    public function via($notifiable)
    {
        return [RuStoreChannel::class];
    }

    /**
     * @param  $notifiable
     * @return RuStoreMessage
     */
    public function toRuStore($notifiable): RuStoreMessage
    {
        return new RuStoreMessage(
            notification: new MessageNotification(
                title: 'Test Push by RuStore',
                body: 'Hello! Test body from RuStoreTestingNotification',
            ),
            android: new MessageAndroid(
                notification: new MessageAndroidNotification(
                    title: 'Android test Push by RuStore',
                    body: 'Hello! Android test body from RuStoreTestingNotification',
                )
            )
        );
    }
}
