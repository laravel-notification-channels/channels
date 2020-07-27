<?php

namespace NotificationChannels\Infobip\Events;

use Illuminate\Notifications\Notification;
use infobip\api\model\sms\mt\send\SMSResponseDetails;

class NotificationSent
{
    protected $notifiable;

    protected $notification;

    private $sentMessageInfo;

    /**
     * @param $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param SMSResponseDetails $sentMessageInfo
     */
    public function __construct($notifiable, Notification $notification, SMSResponseDetails $sentMessageInfo)
    {
        $this->notifiable = $notifiable;
        $this->notification = $notification;
        $this->sentMessageInfo = $sentMessageInfo;
    }
}
