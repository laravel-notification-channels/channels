<?php

namespace NotificationChannels\PubNub;

use NotificationChannels\PubNub\Exceptions\CouldNotSendNotification;
use NotificationChannels\PubNub\Events\MessageWasSent;
use NotificationChannels\PubNub\Events\SendingMessage;
use Illuminate\Notifications\Notification;
use Pubnub\Pubnub;
use Pubnub\PubnubException;

class PubNubChannel
{
    /** @\Pubnub\Pubnub */
    protected $pubnub;

    public function __construct(Pubnub $pubnub)
    {
        $this->pubnub = $pubnub;
    }

    /**
     * Send the given notification.
     *
     * @param   mixed   $notifiable
     * @param   \Illuminate\Notifications\Notification  $notification
     *
     * @throws  \NotificationChannels\PubNub\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if ( ! $this->shouldSendMessage($notifiable, $notification))
            return;

        $message = $notification->toPubnub($notifiable);

        try {
            $this->pubnub->publish($message->channel, $message->content);
        } catch (PubnubException $exception) {
            CouldNotSendNotification::pubnubRespondedWithAnError($exception);
        }

        event(new MessageWasSent($notifiable, $notification));
    }

    /**
     *
     *
     * @param   mixed   $notifiable
     * @param   Notification    $notification
     * @return  bool
     */
    protected function shouldSendMessage($notifiable, Notification $notification)
    {
        return event(new SendingMessage($notifiable, $notification), [], true) !== false;
    }
}
