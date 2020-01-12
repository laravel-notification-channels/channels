<?php

namespace NotificationChannels\AwsSns;

use NotificationChannels\AwsSns\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;

class SnsChannel
{
    /**
     * @var Sns
     */
    protected $sns;

    /**
     * SnsChannel constructor.
     * @param  Sns  $sns
     */
    public function __construct(Sns $sns)
    {
        $this->sns = $sns;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\AwsSns\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        //$response = [a call to the api of your notification send]

//        if ($response->error) { // replace this by the code need to check for errors
//            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
//        }
    }
}
