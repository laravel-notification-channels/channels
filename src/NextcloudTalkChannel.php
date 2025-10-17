<?php

namespace NotificationChannels\NextcloudTalk;

use Illuminate\Notifications\Notification;
use NotificationChannels\NextcloudTalk\Exceptions\CouldNotSendNotification;

class NextcloudTalkChannel
{
    protected $nextcloudTalk;

    public function __construct(NextcloudTalk $nextcloudTalk)
    {
        $this->nextcloudTalk = $nextcloudTalk;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @throws \NotificationChannels\NextcloudTalk\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toNextcloudTalk($notifiable);

        if (! ($message instanceof NextcloudTalkMessage)) {
            throw CouldNotSendNotification::messageNotCorrectInstance();
        }

        $to = $message->getChannel();

        if (! empty($to)) {
            return $this->nextcloudTalk->sendMessage($to, $message->getContent());
        }

        $to = $notifiable->routeNotificationFor('NextcloudTalk');

        if (! empty($to)) {
            return $this->nextcloudTalk->sendMessageToUser($to, $message->getContent());
        }

        $to = $this->nextcloudTalk->getDefaultChannel();
        if (! empty($to)) {
            return $this->nextcloudTalk->sendMessage($to, $message->getContent());
        }

        throw CouldNotSendNotification::missingTo();
    }
}
