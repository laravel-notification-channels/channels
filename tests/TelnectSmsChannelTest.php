<?php

namespace NotificationChannels\Cmsms\Test;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\TelnectSms\TelnectSmsChannel;
use NotificationChannels\TelnectSms\TelnectSmsClient;
use NotificationChannels\TelnectSms\TelnectSmsMessage;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TelnectSmsChannelTest extends TestCase
{
    public static $latestResponse;

    private TestNotification $notification;

    private TestNotifiable $notifiable;

    private Client $guzzle;

    private TelnectSmsClient $client;

    private TelnectSmsChannel $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->notification = new TestNotification;
        $this->notifiable = new TestNotifiable;
        $this->guzzle = Mockery::mock(new Client);
        $this->client = Mockery::mock(new TelnectSmsClient($this->guzzle, '00000FFF-0000-F0F0-F0F0-FFFFFFFFFFFF'));
        $this->channel = new TelnectSmsChannel($this->client);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_can_be_instantiated()
    {
        $this->assertInstanceOf(TelnectSmsClient::class, $this->client);
        $this->assertInstanceOf(TelnectSmsChannel::class, $this->channel);
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function it_shares_message()
    {
        $this->client->shouldReceive('send')->once();
        $this->channel->send($this->notifiable, $this->notification);
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForTelnectSms()
    {
        return '+46733228083';
    }
}

class TestNotification extends Notification
{
    public function toTelnectSms($notifiable)
    {
        return TelnectSmsMessage::create()->body('Message content')
            ->sender('APPNAME');
    }
}
