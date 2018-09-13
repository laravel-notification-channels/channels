<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests\Mocks;

use FtwSoft\NotificationChannels\Intercom\Contracts\IntercomNotification;
use FtwSoft\NotificationChannels\Intercom\IntercomMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification implements IntercomNotification
{
    /**
     * @var IntercomMessage
     */
    private $intercomMessage;

    /**
     * TestNotification constructor.
     *
     * @param \FtwSoft\NotificationChannels\Intercom\IntercomMessage $intercomMessage
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
