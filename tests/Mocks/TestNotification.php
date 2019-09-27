<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests\Mocks;

use FtwSoft\NotificationChannels\Intercom\IntercomMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    /**
     * @var IntercomMessage
     */
    private $intercomMessage;

    /**
     * @param IntercomMessage $intercomMessage
     */
    public function __construct(IntercomMessage $intercomMessage)
    {
        $this->intercomMessage = $intercomMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function toIntercom($notifiable): IntercomMessage
    {
        return $this->intercomMessage;
    }
}
