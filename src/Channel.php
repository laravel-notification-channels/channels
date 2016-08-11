<?php

namespace NotificationChannels\WebPushNotifications;

use Minishlink\WebPush\WebPush;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPushNotifications\Events\MessageWasSent;
use NotificationChannels\WebPushNotifications\Events\SendingMessage;

class Channel
{
    /**
     * @var \Minishlink\WebPush\WebPush
     */
    protected $webPush;

    /**
     * Create a new Web Push channel instance.
     *
     * @param  \Minishlink\WebPush\WebPush $webPush
     * @return void
     */
    public function __construct(WebPush $webPush)
    {
        $this->webPush = $webPush;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $shouldSendMessage = event(new SendingMessage($notifiable, $notification), [], true) !== false;

        if (! $shouldSendMessage) {
            return;
        }

        $subscriptions = $notifiable->routeNotificationFor('WebPushNotifications');

        if ($subscriptions->isEmpty()) {
            return;
        }

        $payload = json_encode($notification->toWebPush($notifiable, $notification)->toArray());

        $subscriptions->each(function ($sub) use ($payload) {
            $this->webPush->sendNotification(
                $sub->endpoint,
                $payload,
                $sub->public_key,
                $sub->auth_token
            );
        });

        $response = $this->webPush->flush();

        // Delete the invalid subscriptions.
        if (is_array($response)) {
            foreach ($response as $index => $value) {
                if (! $value['success'] && isset($subscriptions[$index])) {
                    $subscriptions[$index]->delete();
                }
            }
        }

        event(new MessageWasSent($notifiable, $notification));
    }
}
