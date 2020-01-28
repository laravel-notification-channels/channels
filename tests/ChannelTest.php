<?php

namespace NotificationChannels\Bitrix24\Test;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Bitrix24\Api\Bitrix24;
use NotificationChannels\Bitrix24\Bitrix24Channel;
use NotificationChannels\Bitrix24\Bitrix24Message;
use PHPUnit\Framework\TestCase;

class ChannelTest extends TestCase
{
    /** @var \NotificationChannels\Bitrix24\Api\Bitrix24 */
    protected $bitrix24;

    /** @var \NotificationChannels\Bitrix24\Bitrix24Channel */
    protected $channel;

    public function setUp(): void
    {
        parent::setUp();

        $this->bitrix24 = Mockery::mock(Bitrix24::class);

        $this->channel = new Bitrix24Channel($this->bitrix24);
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->bitrix24->shouldReceive('send')->with([
            'USER_ID' => 56,
            'MESSAGE' => 'message',
        ])->once();

        $this->channel->send(new TestNotifiable(), new TestNotification());
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForBitrix24(): int
    {
        return 56;
    }
}

class TestNotification extends Notification
{
    public function toBitrix24($notifiable)
    {
        return (new Bitrix24Message())
            ->text('message')
            ->toUser();
    }
}