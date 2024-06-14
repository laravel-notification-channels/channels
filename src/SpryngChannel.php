<?php

namespace NotificationChannels\Spryng;

use Illuminate\Notifications\Notification;
use NotificationChannels\Spryng\Exceptions\CouldNotSendNotification;
use Spryng\SpryngRestApi\Objects\Message;
use Spryng\SpryngRestApi\Spryng;

class SpryngChannel
{
    public function __construct(
        private Spryng $spryng
    ) {
        $this->spryng = $spryng;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @throws \NotificationChannels\Spryng\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $to = $notifiable->phone;

        if (! $to) {
            return;
        }

        $message = $notification->toSpryng($notifiable);

        $message = $this->createMessage($message);

        $response = $this->spryng->message->create($message);

        if (! $response || $response->serverError()) {
            throw new CouldNotSendNotification(
                message: 'Message could not be send because of a server error...',
                code: $response->getResponseCode()
            );
        }
    }

    private function createMessage(SpryngMessage $message): Message
    {
        $spryngMessage = new Message();

        $spryngMessage->setBody($message->body);
        $spryngMessage->setRecipients($message->recipients);
        $spryngMessage->setOriginator($message->originator);

        return $spryngMessage;
    }
}
