<?php

namespace NotificationChannels\GoogleChat\Tests\Fixtures;

use Illuminate\Notifications\Notification;
use NotificationChannels\GoogleChat\GoogleChatChannel;
use NotificationChannels\GoogleChat\GoogleChatMessage;

class TestNotification extends Notification
{
    private $space;

    public function setSpace(string $space)
    {
        $this->space = $space;

        return $this;
    }

    public function via($notifiable)
    {
        return [GoogleChatChannel::class];
    }

    public function toGoogleChat($notifiable)
    {
        $message = GoogleChatMessage::create('Example Message');

        if ($this->space) {
            $message->to($this->space);
        }

        return $message;
    }
}
