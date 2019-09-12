<?php

namespace NotificationChannels\FortySixElks;

use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\FortySixElks\Exceptions\CouldNotUseNotification;

/**
 * Class FortySixElksChannel.
 */
class FortySixElksChannel
{
    protected $events;

    /**
     * FortySixElksChannel constructor.
     */
    public function __construct(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param mixed                                  $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\FortySixElks\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'to46Elks')) {
            if ($media = $notification->to46Elks($notifiable)) {
                try {
                    $media->send();
                } catch (\Exception $e) {
                    $this->events->dispatch(new NotificationFailed($notifiable, $notification, get_class($this), ['exception' => $e]));
                }
            }
        } else {
            throw CouldNotUseNotification::missingMethod();
        }
    }
}
