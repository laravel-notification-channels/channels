<?php

namespace NotificationChannels\Cmsms\Test;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Cmsms\CmsmsChannel;
use NotificationChannels\Cmsms\CmsmsClient;
use NotificationChannels\Cmsms\CmsmsMessage;
use PHPUnit_Framework_TestCase;

class CmsmsChannelTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->notification = new TestNotification;
        $this->notifiable = new TestNotifiable;
        $this->guzzle = Mockery::mock(new Client());
        $this->client = Mockery::mock(new CmsmsClient($this->guzzle, '00000FFF-0000-F0F0-F0f0-FFFFFFFFFFFF'));
        $this->channel = new CmsmsChannel($this->client);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(CmsmsClient::class, $this->client);
        $this->assertInstanceOf(CmsmsChannel::class, $this->channel);
    }

    /** @test */
    public function it_shares_message()
    {
        $this->client->shouldReceive('send')->once();
        $this->channel->send($this->notifiable, $this->notification);
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForCmsms()
    {
        return '0031612345678';
    }
}

class TestNotification extends Notification
{
    public function toCmsms($notifiable)
    {
        return (new CmsmsMessage('Message content'))->setOriginator('APPNAME')->setRecipient('0031612345678');
    }
}
