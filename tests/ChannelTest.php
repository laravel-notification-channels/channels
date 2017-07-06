<?php

namespace NotificationChannels\RealtimePushNotifications\Test;

use Illuminate\Notifications\Notification;
use NotificationChannels\RealtimePushNotifications\RealtimeChannel;
use NotificationChannels\RealtimePushNotifications\RealtimeMessage;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \Config::set('services.realtimepush.applicationKey', 'INSERT_YOUR_REALTIME_APPKEY');
        \Config::set('services.realtimepush.privateKey', 'INSERT_YOUR_REALTIME_PRIVATEKEY');
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $channel = new RealtimeChannel();
        $channel->send(new TestNotifiable(), new TestNotification());
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForRealtimePush()
    {
        return 'INSERT_YOUR_CHANNEL_SUBSCRIBED_WITH_PUSH_NOTIFICATIONS';
    }
}

class TestNotification extends Notification
{
    public function toRealtimePushMesssage($notifiable)
    {
        return RealtimeMessage::create()
            ->iosTitle('Realtime Custom Push Notifications')
            ->iosSubtitle('Now with iOS 10 support!')
            ->iosBody('Add multimedia content to your notifications')
            ->sound('default')
            ->badge(1)
            ->iosMutableContent(1)
            ->iosAttachmentUrl('https://framework.realtime.co/blog/img/ios10-video.mp4')
            ->androidMessage('Realtime Custom Push Notifications')
            ->androidPayload(['foo'=>'bar']);
    }
}