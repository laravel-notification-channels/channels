<?php

namespace NotificationChannels\SmsCentre;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use NotificationChannels\SmsCentre\Events\SendingMessage;
use NotificationChannels\SmsCentre\Events\MessageWasSent;
use NotificationChannels\SmsCentre\Exceptions\CouldNotSendNotification;

class SmsCentreChannel
{
    protected $smsc;

    public function __construct(SmsCentre $smsc)
    {
        $this->smsc = $smsc;
    }

    /**
     * Send the given notification.
     *
     * @param  Notifiable    $notifiable
     * @param  Notification  $notification
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $this->shouldSendMessage($notifiable, $notification)) {
            return;
        }

        if (! $to = $notifiable->routeNotificationFor('smscentre')) {
            return;
        }

        /** @var SmsCentreMessage $message */
        $message = $notification->toSmsCentre($notifiable);

        if (is_string($message)) {
            $message = new SmsCentreMessage($message);
        }

        $this->smsc->send($to, $message->toArray());

        event(new MessageWasSent($notifiable, $notification));
    }

    /**
     * Check if we can send the notification.
     *
     * @param  Notifiable    $notifiable
     * @param  Notification  $notification
     *
     * @return bool
     */
    protected function shouldSendMessage($notifiable, $notification)
    {
        return event(new SendingMessage($notifiable, $notification), [], true) !== false;
    }
}
