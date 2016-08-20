<?php

namespace NotificationChannels\Gammu;

use NotificationChannels\Gammu\Exceptions\CouldNotSendNotification;
//use NotificationChannels\Gammu\Events\MessageWasSent;
//use NotificationChannels\Gammu\Events\SendingMessage;
use NotificationChannels\Gammu\Models\Outbox;
use Illuminate\Notifications\Notification;

class GammuChannel
{
    /**
     * @var Outbox
     */
    protected $outbox;
    
    /**
     * Channel constructor.
     *
     * @param Outbox $outbox
     */
    public function __construct(Outbox $outbox)
    {
        $this->outbox = $outbox;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param $notification
     */
    public function send($notifiable, Notification $notification)
    {
        $payload = $notification->toGammu($notifiable)->toArray();
        
        $this->outbox->create($payload);
    }
}
