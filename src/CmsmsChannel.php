<?php

namespace NotificationChannels\Cmsms;

use Illuminate\Notifications\Notification;

class CmsmsChannel
{
    /**
     * @var \NotificationChannels\Cmsms\CmsmsClient
     */
    protected $client;

    /**
     * @param CmsmsClient $client
     */
    public function __construct(CmsmsClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Cmsms\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toCmsms($notifiable);

        if (is_string($message)) {
            $message = CmsmsMessage::create($message);
        }

        $this->client->send($message);
    }
}
