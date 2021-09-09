<?php

namespace NotificationChannels\ExpoPushNotifications\Test;

use ExponentPhpSDK\Exceptions\ExpoException;
use ExponentPhpSDK\Expo;
use Illuminate\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class ChannelTest extends TestCase
{
    /**
     * @var Expo
     */
    protected $expo;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * @var ExpoChannel
     */
    protected $channel;

    /**
     * @var TestNotification
     */
    protected $notification;

    /**
     * @var TestNotifiable
     */
    protected $notifiable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->expo = Mockery::mock(Expo::class);

        $this->events = Mockery::mock(Dispatcher::class);

        $this->channel = new ExpoChannel($this->expo, $this->events);

        $this->notification = new TestNotification;

        $this->notifiable = new TestNotifiable;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    /** @test */
    public function itCanSendANotification()
    {
        $message = $this->notification->toExpoPush($this->notifiable);

        $data = $message->toArray();

        $this->expo->shouldReceive('notify')->with(['interest_name'], $data, true)->andReturn([['status' => 'ok']]);

        $this->channel->send($this->notifiable, $this->notification);
    }

    /** @test */
    public function itFiresFailureEventOnFailure()
    {
        $message = $this->notification->toExpoPush($this->notifiable);

        $data = $message->toArray();

        $this->expo->shouldReceive('notify')->with(['interest_name'], $data, true)->andThrow(ExpoException::class, '');

        $this->events->shouldReceive('dispatch')->with(Mockery::type(NotificationFailed::class));

        $this->channel->send($this->notifiable, $this->notification);
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForExpoPushNotifications()
    {
        return 'interest_name';
    }

    public function getKey()
    {
        return 1;
    }
}

class TestNotification extends Notification
{
    public function toExpoPush($notifiable)
    {
        return new ExpoMessage();
    }
}
