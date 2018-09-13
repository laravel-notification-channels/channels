<?php
namespace FtwSoft\NotificationChannels\Intercom\Contracts;

use FtwSoft\NotificationChannels\Intercom\IntercomMessage;

interface IntercomNotification
{

    /**
     * Make notification to the Intercom channel
     *
     * @param mixed $notifiable
     *
     * @return IntercomMessage
     */
    public function toIntercom($notifiable): IntercomMessage;

}