<?php

namespace NotificationChannels\Chatwork;

use Illuminate\Notifications\Notification;
use NotificationChannels\Chatwork\Exceptions\CouldNotSendNotification;

class ChatworkChannel
{
    protected $chatwork;

    public function __construct(Chatwork $chatwork)
    {
        $this->chatwork = $chatwork;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \NotificationChannels\Chatwork\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $chatworkMessage = $notification->toChatwork($notifiable);

        $roomId = $notifiable->routeNotificationFor('chatwork');
        if (empty($roomId)) {
            $roomId = $chatworkMessage->roomId;
        }

        if (empty($roomId)) {
            return;
        }

        $sendText = '';
        if (is_a($chatworkMessage, ChatworkMessage::class)) {
            // normal message
            $sendText .= $chatworkMessage->message;
        } else {
            // information message
            $sendText .= '[info][title]'.$chatworkMessage->informationTitle.'[/title]';
            $sendText .= $chatworkMessage->informationMessage;
            $sendText .= '[/info]';
        }

        $params = [];
        $params['room_id'] = $roomId;
        $params['text'] = $sendText;

        $result = $this->chatwork->sendMessage($params);
        if (! $result) {
            throw CouldNotSendNotification::serviceRespondedWithAnError(null);
        }
    }
}
