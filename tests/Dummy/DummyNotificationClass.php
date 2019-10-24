<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/2/2019
 * Time: 5:34 PM.
 */

namespace NotificationChannels\MoceanApi\Test\Dummy;

use Illuminate\Notifications\Notification;

class DummyNotificationClass extends Notification
{
    public function toMoceanapi($notifiable)
    {
        return 'testing message';
    }
}
