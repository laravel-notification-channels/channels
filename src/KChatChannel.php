<?php

namespace NotificationChannels\KChat;

use Illuminate\Notifications\Notification;
use NotificationChannels\KChat\Exceptions\CouldNotSendNotification;

class KChatChannel
{
    /**
     * @var KChat
     */
    protected $kChat;

    /**
     * Channel constructor.
     *
     * @param  KChat  $kChat
     */
    public function __construct(KChat $kChat)
    {
        $this->kChat = $kChat;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toKChat($notifiable);

        // if the recipient is not defined get it from the notifiable object
        if ($message->toNotGiven()) {
            $to = $notifiable->routeNotificationFor('kChat', $notification);

            if (is_null($to)) {
                throw CouldNotSendNotification::channelMissing();
            }
            $message->to($to);
        }

        $response = $this->kChat->send($message->toArray());

        return $response;
    }
}
