<?php

namespace NotificationChannels\Bonga\Test;

use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Bonga\BongaChannel;
use NotificationChannels\Bonga\BongaMessage;
use NotificationChannels\Bonga\Exceptions\CouldNotSendNotification;
use Osen\Bonga\Sms;

class BongaChannelTest extends TestCase
{
    /** @var Mockery\Mock */
    protected $bonga;

    /** @var \NotificationChannels\Twitter\BongaChannel */
    protected $channel;

    public function setUp(): void
    {
        parent::setUp();
        $this->bonga = Mockery::mock(BongaSDK::class);
        $this->channel = new BongaChannel($this->bonga);
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Sms::class, $this->bonga);
        $this->assertInstanceOf(BongaChannel::class, $this->channel);
    }

    /** @test */
    public function it_can_send_sms_notification()
    {
        $this->bonga->shouldReceive('send')
            ->once()
            ->andReturn(200);

        $this->channel->send(new TestNotifiable, new TestNotification);
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForBonga()
    {
        return '+254705459494';
    }
}

class TestNotification extends Notification
{
    /**
     * @param $notifiable
     * @return BongaMessage
     * @throws CouldNotSendNotification
     */
    public function toBonga($notifiable)
    {
        return new BongaMessage();
    }
}