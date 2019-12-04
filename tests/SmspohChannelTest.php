<?php

namespace NotificationChannels\Smspoh\Tests;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery as m;
use NotificationChannels\Smspoh\Exceptions\CouldNotSendNotification;
use NotificationChannels\Smspoh\SmspohApi;
use NotificationChannels\Smspoh\SmspohChannel;
use NotificationChannels\Smspoh\SmspohMessage;

class SmspohChannelTest extends TestCase
{
    public function tearDown(): void
    {
        M::close();
    }

    /** @test */
    public function it_can_send_a_notification(): void
    {
        $channel = new SmspohChannel($smspoh = m::mock(SmspohApi::class), '4444444444');

        $smspoh->shouldReceive('send')->with([
            'sender' => '5554443333',
            'to' => '5555555555',
            'message' => 'this is my message',
            'test' => 0,
        ])->once();

        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_can_send_string_message(): void
    {
        $channel = new SmspohChannel($smspoh = m::mock(SmspohApi::class), '4444444444');

        $smspoh->shouldReceive('send')->once();

        $channel->send(new TestNotifiable(), new TestNotificationStringMessage());
    }

    /** @test */
    public function it_does_not_send_a_message_when_to_missed(): void
    {
        $channel = new SmspohChannel($smspoh = m::mock(SmspohApi::class), '4444444444');

        $smspoh->shouldNotReceive('send');

        $channel->send(new TestNotifiableWithoutRouteNotificationForSmspoh(), new TestNotification());
    }

    /** @test */
    public function it_can_check_content_length_limit(): void
    {
        $this->withoutExceptionHandling();

        $character_count = 160;

        $channel = new SmspohChannel(new SmspohApi(), '4444444444');

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionCode(422);
        $this->expectExceptionMessage("Notification was not sent. Content length may not be greater than {$character_count} characters.");

        $channel->send(new TestNotifiable(), new TestNotificationTooLongMessage());
    }
}

class TestNotifiable
{
    use Notifiable;

    public $phone_number = '5555555555';

    public function routeNotificationForSmspoh($notification)
    {
        return $this->phone_number;
    }
}

class TestNotifiableWithoutRouteNotificationForSmspoh extends TestNotifiable
{
    public function routeNotificationForSmspoh($notification)
    {
        return false;
    }
}

class TestNotification extends Notification
{
    public function toSmspoh($notifiable)
    {
        return (new SmspohMessage('this is my message'))->sender('5554443333');
    }
}

class TestNotificationTooLongMessage extends Notification
{
    public function toSmspoh($notifiable)
    {
        return (new SmspohMessage('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'))->sender('5554443333');
    }
}

class TestNotificationStringMessage extends Notification
{
    public function toSmspoh($notifiable)
    {
        return 'this is my message';
    }
}
