<?php

namespace NotificationChannels\Infobip;

use Illuminate\Notifications\Notification;
use NotificationChannels\Infobip\Events\NotificationFailed;
use NotificationChannels\Infobip\Events\NotificationSent;
use NotificationChannels\Infobip\Exceptions\CouldNotSendNotification;

class InfobipChannel
{
    public $infobip;

    /**
     * InfobipChannel constructor.
     *
     * @param Infobip $infobip
     */
    public function __construct(Infobip $infobip)
    {
        $this->infobip = $infobip;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Infobip\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $recipient = $this->getRecipient($notifiable);
        $message = $notification->toInfoBip($notifiable);

        try {
            $response = $this->infobip->sendMessage($message, $recipient);
            $sentMessageInfo = $response->getMessages()[0];

            $this->events->fire(new NotificationSent($notifiable, $notification, $sentMessageInfo));
        } catch (\Exception $exception) {
            $this->events->fire(new NotificationFailed($notifiable, $notification, $exception));
        }
    }

    /**
     * Get message recipient.
     *
     * @param $notifiable
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function getRecipient($notifiable)
    {
        if ($notifiable->routeNotificationForInfoBip()) {
            return $notifiable->routeNotificationForInfoBip();
        }

        throw CouldNotSendNotification::invalidReceiver();
    }
}
