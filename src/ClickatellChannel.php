<?php

namespace NotificationChannels\Clickatell;

use NotificationChannels\Clickatell\Exceptions\CouldNotSendNotification;
use NotificationChannels\Clickatell\Events\MessageWasSent;
use NotificationChannels\Clickatell\Events\SendingMessage;
use Illuminate\Notifications\Notification;

class ClickatellChannel
{
    /**
     * @var ClickatellClient
     */
    private $clickatell;

    /**
     * @param ClickatellClient $clickatell
     */
    public function __construct(ClickatellClient $clickatell)
    {
        $this->clickatell = $clickatell;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$this->shouldSendMessage($notifiable, $notification)) {
            return;
        }

        if (!$to = $notifiable->routeNotificationFor('clickatell')) {
            return;
        }

        $message = $notification->toClickatell($notifiable);

        if (is_string($message)) {
            $message = new ClickatellMessage($message);
        }

        $this->clickatell->send($to, $message->getContent());

        event(new MessageWasSent($notifiable, $notification));
    }

    /**
     * Check if we can send the notification.
     *
     * @param $notifiable
     * @param   Notification $notification
     *
     * @return bool
     */
    protected function shouldSendMessage($notifiable, Notification $notification)
    {
        return event(new SendingMessage($notifiable, $notification), [], true) !== false;
    }
}
