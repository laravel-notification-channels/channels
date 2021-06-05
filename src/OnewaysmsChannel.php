<?php

namespace NotificationChannels\Onewaysms;

use Illuminate\Notifications\Notification;
use NotificationChannels\Onewaysms\Exceptions\CouldNotSendNotification;

class OnewaysmsChannel
{
    /**
     * The Onewaysms client instance.
     *
     * @var OnewaysmsApi
     */
    protected $onewaysms;

    /**
     * The sender id.
     *
     * @var string
     */
    protected $sender;

    /**
     * @var int
     * 
     * The message body content count should be no longer than 3 message parts (459).
     */
    protected $character_limit_count = 459;

    public function __construct(OnewaysmsApi $onewaysms, $sender)
    {
        $this->onewaysms = $onewaysms;
        $this->sender = $sender;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface|void
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('onewaysms', $notification)) {
            return;
        }

        $message = $notification->toOnewaysms($notifiable);

        if (is_string($message)) {
            $message = new OnewaysmsMessage($message);
        }

        if (mb_strlen($message->content) > $this->character_limit_count) {
            throw CouldNotSendNotification::contentLengthLimitExceeded($this->character_limit_count);
        }

        return $this->onewaysms->send([
            'sender' => $message->sender ?: $this->sender,
            'to' => $to,
            'message' => trim($message->content),
        ]);
    }
}