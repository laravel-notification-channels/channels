<?php

namespace NotificationChannels\Unifonic\Test;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Unifonic\UnifonicChannel;
use NotificationChannels\Unifonic\UnifonicClient;
use NotificationChannels\Unifonic\UnifonicMessage;
use PHPUnit\Framework\TestCase;

class UnifonicChannelTest extends TestCase
{
    protected $appsId;

    public function setUp(): void
    {
        parent::setUp();

        $this->notification = new TestNotification();
        $this->notifiable = new TestNotifiable();
        $this->guzzle = Mockery::mock(new Client());
        $this->appsId = '1335b3f7-ba23-4316-b2dd-202448bd8904';
        // Config::set('services.unifonic.appsId', $this->appsId);
        $this->client = Mockery::mock(new UnifonicClient($this->guzzle, $this->appsId));
        $this->channel = new UnifonicChannel($this->client);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(UnifonicClient::class, $this->client);
        $this->assertInstanceOf(UnifonicChannel::class, $this->channel);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_send_message()
    {
        $this->client->shouldReceive('send')->once();
        $this->channel->send($this->notifiable, $this->notification);
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForUnifonic()
    {
        return '212679064497';
    }
}

class TestNotification extends Notification
{
    public function toUnifonic($notifiable)
    {
        return UnifonicMessage::create('Message content');
    }
}
