<?php

namespace NotificationChannels\Wechat;

use Illuminate\Notifications\Notification;

class WeChatChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $notification->toWechat($notifiable)->send();
    }
}
