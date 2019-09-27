<?php

namespace FtwSoft\NotificationChannels\Intercom\Contracts;

use FtwSoft\NotificationChannels\Intercom\IntercomMessage;

/**
 * Interface IntercomNotification
 * @package FtwSoft\NotificationChannels\Intercom\Contracts
 *
 * @deprecated Since 1.2.0. Just avoid to use this. Will be removed in 2.0.0 version
 */
interface IntercomNotification
{
    /**
     * Make notification to the Intercom channel.
     *
     * @param mixed $notifiable
     *
     * @return IntercomMessage
     */
    public function toIntercom($notifiable): IntercomMessage;
}
