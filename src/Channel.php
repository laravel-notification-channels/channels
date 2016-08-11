<?php

namespace NotificationChannels\PushoverNotifications;

use NotificationChannels\PushoverNotifications\Events\MessageWasSent;
use NotificationChannels\PushoverNotifications\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class Channel
{
    /**
     * @var Pushover
     */
    protected $pushover;

    /**
     * Create a new Pushover channel instance.
     *
     * @param  Pushover  $pushover
     * @return void
     */
    public function __construct(Pushover $pushover)
    {
        $this->pushover = $pushover;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\PushoverNotifications\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $shouldSendMessage = event(new SendingMessage($notifiable, $notification), [], true) !== false;

        if (! $shouldSendMessage) {
            return;
        }

        if (! $pushoverKey = $notifiable->routeNotificationFor('pushover')) {
            return;
        }

        $message = $notification->toPushover($notifiable);

        $this->pushover->send([
            'user' => $pushoverKey,
            'device' => null, // in routeNotificationFor, possibility for ['key'=>'', 'device'=>'']
            'message' => $message->content,
            'title' => $message->title,
            'timestamp' => $message->timestamp,
            'priority' => $message->priority,
            'url' => $message->url,
            'url_title' => $message->urlTitle,
            'sound' => $message->sound,
            'retry' => $message->retry,
            'expire' => $message->expire,
        ]);

        event(new MessageWasSent($notifiable, $notification));
    }
}
