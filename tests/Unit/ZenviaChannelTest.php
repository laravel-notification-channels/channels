<?php

namespace NotificationChannels\Twilio\Tests\Unit;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\LaravelZenviaChannel\Exceptions\CouldNotSendNotification;
use NotificationChannels\LaravelZenviaChannel\Zenvia;
use NotificationChannels\LaravelZenviaChannel\ZenviaChannel;
use NotificationChannels\LaravelZenviaChannel\ZenviaConfig;

class ZenviaChannelTest extends MockeryTestCase
{
    /** @var ZenviaChannel */
    protected $channel;

    /** @var Zenvia */
    protected $zenvia;

    /** @var Dispatcher */
    protected $dispatcher;

    public function setUp(): void
    {
        parent::setUp();

        $this->zenvia = Mockery::mock(Zenvia::class);
        $this->dispatcher = Mockery::mock(Dispatcher::class);

        $this->channel = new ZenviaChannel($this->zenvia, $this->dispatcher);
    }

    /** @test */
    public function it_will_not_send_a_message_without_known_receiver()
    {
        $notifiable = new Notifiable();
        $notification = Mockery::mock(Notification::class);

        $this->zenvia->config = new ZenviaConfig([]);

        $this->dispatcher->shouldReceive('dispatch')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->expectException(CouldNotSendNotification::class);

        $result = $this->channel->send($notifiable, $notification);

        $this->assertNull($result);
    }

    /** @test */
    public function it_will_fire_an_event_in_case_of_an_invalid_message()
    {
        $notifiable = new NotifiableWithAttribute();
        $notification = Mockery::mock(Notification::class);

        $this->zenvia->config = new ZenviaConfig([]);

        // Invalid message
        $notification->shouldReceive('toZenvia')->andReturn(-1);

        $this->dispatcher->shouldReceive('dispatch')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->expectException(CouldNotSendNotification::class);

        $this->channel->send($notifiable, $notification);
    }
}

class Notifiable
{
    public $phone_number = null;

    public function routeNotificationFor()
    {
    }
}

class NotifiableWithAttribute
{
    public $phone_number = '+22222222222';

    public function routeNotificationFor()
    {
    }
}
