<?php

namespace NotificationChannels\Onewaysms\Tests;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery as m;
use NotificationChannels\Onewaysms\Exceptions\CouldNotSendNotification;
use NotificationChannels\Onewaysms\OnewaysmsApi;
use NotificationChannels\Onewaysms\OnewaysmsChannel;
use NotificationChannels\Onewaysms\OnewaysmsMessage;

class OnewaysmsChannelTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_send_a_notification(): void
    {
        $channel = new OnewaysmsChannel($ows = m::mock(OnewaysmsApi::class), '4444444444');

        $ows->shouldReceive('send')->with([
            'sender' => '5554443333',
            'to' => '5555555555',
            'message' => 'this is my message'
        ])->once();

        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_can_send_string_message(): void
    {
        $channel = new OnewaysmsChannel($ows = m::mock(OnewaysmsApi::class), '4444444444');

        $ows->shouldReceive('send')->once();

        $channel->send(new TestNotifiable(), new TestNotificationStringMessage());
    }

    /** @test */
    public function it_does_not_send_a_message_when_to_missed(): void
    {
        $channel = new OnewaysmsChannel($ows = m::mock(OnewaysmsApi::class), '4444444444');

        $ows->shouldNotReceive('send');

        $channel->send(new TestNotifiableWithoutRouteNotificationForOnewaysms(), new TestNotification());
    }

    /** @test */
    public function it_can_check_long_content_length(): void
    {
        $this->withoutExceptionHandling();

        $character_count = 459;

        $channel = new OnewaysmsChannel(new OnewaysmsApi(), '4444444444');

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionCode(422);
        $this->expectExceptionMessage("Notification was not sent. Content length may not be greater than {$character_count} characters.");

        $channel->send(new TestNotifiable(), new TestNotificationTooLongMessage());
    }

    /** @test */
    public function it_can_check_limit_count_content(): void
    {
        $channel = new OnewaysmsChannel($ows = m::mock(OnewaysmsApi::class), '4444444444');

        $ows->shouldReceive('send')->once();

        $channel->send(new TestNotifiable(), new TestNotificationLimitCountMessage());
    }
}

class TestNotifiable
{
    use Notifiable;

    public $phone_number = '5555555555';

    public function routeNotificationForOnewaysms($notification)
    {
        return $this->phone_number;
    }
}

class TestNotifiableWithoutRouteNotificationForOnewaysms extends TestNotifiable
{
    public function routeNotificationForOnewaysms($notification)
    {
        return false;
    }
}

class TestNotification extends Notification
{
    public function toOnewaysms($notifiable)
    {
        return (new OnewaysmsMessage('this is my message'))->sender('5554443333');
    }
}

class TestNotificationTooLongMessage extends Notification
{
    public function toOnewaysms($notifiable)
    {
        return (new OnewaysmsMessage('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.'))->sender('5554443333');
    }
}

class TestNotificationLimitCountMessage extends Notification
{
    public function toOnewaysms($notifiable)
    {
        return (new OnewaysmsMessage('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.Lorem ipsum dolor sit amete.'))->sender('5554443333');
    }
}

class TestNotificationStringMessage extends Notification
{
    public function toOnewaysms($notifiable)
    {
        return 'this is my message';
    }
}
