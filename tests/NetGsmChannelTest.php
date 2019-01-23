<?php

namespace NotificationChannels\NetGsm\Test;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\NetGsm\NetGsmChannel;
use NotificationChannels\NetGsm\NetGsmClient;
use NotificationChannels\NetGsm\NetGsmMessage;
use PHPUnit_Framework_TestCase;

class NetGsmChannelTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->notification = new TestNotification;
        $this->string_notification = new TestStringNotification;
        $this->notifiable = new TestNotifiable;
        $this->guzzle = Mockery::mock(new Client());

        $userCode = "";
        $secret = "";
        $msgHeader = "";

        $this->client = Mockery::mock(new NetGsmClient($this->guzzle, $userCode, $secret, $msgHeader));
        $this->channel = new NetGsmChannel($this->client);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(NetGsmClient::class, $this->client);
        $this->assertInstanceOf(NetGsmChannel::class, $this->channel);
    }

    /** @test */
    public function test_it_shares_message()
    {
        $this->client->shouldReceive('send')->once();
        $this->channel->send($this->notifiable, $this->notification);
    }

    /** @test */
    public function if_string_message_can_be_send()
    {
        $this->client->shouldReceive('send')->once();
        $this->channel->send($this->notifiable, $this->string_notification);
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