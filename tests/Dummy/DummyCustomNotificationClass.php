<?php
/**
 * Created by PhpStorm.
 * User: Neoson Lam
 * Date: 7/3/2019
 * Time: 9:34 AM.
 */

namespace NotificationChannels\MoceanApi\Test\Dummy;

use Illuminate\Notifications\Notification;

class DummyCustomNotificationClass extends Notification
{
    public function toMoceanapi($notifiable)
    {
        return [
            'mocean-text' => 'Hello World',
            'mocean-dlr-url' => 'http://test.com',
        ];
    }
}
