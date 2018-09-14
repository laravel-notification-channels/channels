<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests\Mocks;

use Illuminate\Notifications\Notification;
use FtwSoft\NotificationChannels\Intercom\IntercomMessage;
use FtwSoft\NotificationChannels\Intercom\Contracts\IntercomNotification;

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
