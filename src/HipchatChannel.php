<?php

namespace NotificationChannels\Hipchat;

use GuzzleHttp\Exception\ClientException;
use NotificationChannels\Hipchat\Events\MessageWasSent;
use NotificationChannels\Hipchat\Events\SendingMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Hipchat\Exceptions\CouldNotSendNotification;

class HipchatChannel
{
    /**
     * The Hipchat client instance.
     *
     * @var \NotificationChannels\Hipchat\Hipchat
     */
    protected $hipchat;

    /**
     * Create a HipchatChannel instance.
     *
     * @param \NotificationChannels\Hipchat\Hipchat
     */
    public function __construct(Hipchat $hipchat)
    {
        $this->hipchat = $hipchat;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Hipchat\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toHipchat($notifiable);

        if (is_string($message)) {
            $message = new HipchatMessage($message);
        }

        if (! in_array(get_class($message), [HipchatMessage::class])) {
            throw CouldNotSendNotification::invalidMessageObject($message);
        }

        $to = $message->room ?: $notifiable->routeNotificationFor('hipchat');
        if (! $to = $to ?: $this->hipchat->room()) {
            throw CouldNotSendNotification::missingTo();
        }

        if (event(new SendingMessage($notifiable, $notification), [], true) === false) {
            return;
        }

        try {
            $this->sendMessage($to, $message);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::hipchatRespondedWithAnError($exception);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::internalError();
        }

        event(new MessageWasSent($notifiable, $notification));
    }

    /**
     * Send the Hipchat notification message.
     *
     * @param $to
     * @param mixed $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendMessage($to, $message)
    {
        if ($message instanceof HipchatMessage) {
            return $this->hipchat->sendMessage($to, $message->toArray());
        }
    }
}
