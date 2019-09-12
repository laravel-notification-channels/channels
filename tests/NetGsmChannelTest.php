<?php

namespace NotificationChannels\NetGsm\Test;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\NetGsm\NetGsmChannel;
use NotificationChannels\NetGsm\NetGsmClient;
use NotificationChannels\NetGsm\NetGsmMessage;
use PHPUnit\Framework\TestCase;

class NetGsmChannelTest extends TestCase
{
    /** @var NetGsmClient */
    protected $client;

    /** @var Client */
    protected $guzzle;

    /** @var NetGsmChannel */
    protected $channel;

    /** @var TestNotification */
    protected $notification;

    /** @var TestNotifiable */
    protected $notifiable;

    /** @var TestStringNotification */
    protected $string_notification;

    public function setUp(): void
    {
        $this->notification = new TestNotification;
        $this->string_notification = new TestStringNotification;
        $this->notifiable = new TestNotifiable;
        $this->guzzle = Mockery::mock(new Client());

        $userCode = '';
        $secret = '';
        $msgHeader = '';

        $this->client = Mockery::mock(new NetGsmClient($this->guzzle, $userCode, $secret, $msgHeader));
        $this->channel = new NetGsmChannel($this->client);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $this->assertInstanceOf(NetGsmClient::class, $this->client);
        $this->assertInstanceOf(NetGsmChannel::class, $this->channel);
    }

    /** @test */
    public function test_it_shares_message(): void
    {
        $this->client->shouldReceive('send')->once();
        $this->channel->send($this->notifiable, $this->notification);

        $this->assertEquals(1, $this->client->mockery_getExpectationCount());
    }

    /** @test */
    public function if_string_message_can_be_send(): void
    {
        $this->client->shouldReceive('send')->once();
        $this->channel->send($this->notifiable, $this->string_notification);

        $this->assertEquals(1, $this->client->mockery_getExpectationCount());
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForNetGsm()
    {
        return '31650520659';
    }
}

class TestNotification extends Notification
{
    public function toNetGsm($notifiable)
    {
        return (new NetGsmMessage('Message content'))->setHeader('COMPANY')->setRecipients('31650520659');
    }
}

class TestStringNotification extends Notification
{
    public function toNetGsm($notifiable)
    {
        return 'Test by string';
    }
}
