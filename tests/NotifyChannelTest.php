<?php

namespace NotificationChannels\Notify\Test;

use GuzzleHttp\Client;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Response;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Notify\Exceptions\CouldNotSendNotification;
use NotificationChannels\Notify\Exceptions\InvalidConfiguration;
use NotificationChannels\Notify\Exceptions\InvalidMessageObject;
use NotificationChannels\Notify\NotifyChannel;
use NotificationChannels\Notify\NotifyClient;
use NotificationChannels\Notify\NotifyMessage;
use Orchestra\Testbench\TestCase;

class NotifyChannelTest extends TestCase
{
    public function setUp()
    {
        $this->notification = new TestNotification;
        $this->notifiable = new TestNotifiable;
        $this->emptyNotifiable = new EmptyTestNotifiable();
        $this->guzzle = Mockery::mock(new Client());
        $this->dispatcher = Mockery::mock(new Dispatcher());
        $this->client = Mockery::mock(new NotifyClient($this->guzzle));
        $this->channel = new NotifyChannel($this->client, $this->dispatcher);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(NotifyClient::class, $this->client);
        $this->assertInstanceOf(NotifyChannel::class, $this->channel);
    }

    /** @test */
    public function it_will_not_send_a_message_without_known_recipient()
    {
        $this->dispatcher->shouldReceive('fire')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationFailed::class));
        $result = $this->channel->send($this->emptyNotifiable, $this->notification);
        $this->assertNull($result);
    }

    /** @test */
    public function it_will_fire_an_event_in_case_of_an_invalid_message()
    {
        $notification = Mockery::mock(Notification::class);
        // Invalid message
        $notification->shouldReceive('toNotify')->andReturn(-1);
        $this->dispatcher->shouldReceive('fire')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationFailed::class));
        $result = $this->channel->send($this->emptyNotifiable, $notification);
        $this->assertNull($result);
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $response = new Response('{ "success": true, "identifier": "015da530-e867-11e8-9580-3b2b4d2b2a6b" }', 200);
        $this->client->shouldReceive('send')->andReturn($response);
        $result = $this->channel->send($this->notifiable, $this->notification);
        $this->assertSame($response, $result);
    }

    /** @test */
    public function it_does_not_send_a_message_when_notifiable_does_not_have_route_notificaton_for_notify()
    {
        $this->client->shouldReceive('send')->never();
        $result = $this->channel->send($this->emptyNotifiable, $this->notification);
        $this->assertNull($result);
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationFor()
    {
        return [
            'name' => 'test',
            'recipient' => 'test@test.com',
        ];
    }
}

class EmptyTestNotifiable
{
    public function routeNotificationFor()
    {
        return '';
    }
}

class TestNotification extends Notification
{
    public function toNotify($notifiable)
    {
        return (new NotifyMessage())
            ->setNotificationType('notificationType')
            ->setTransport('mail')
            ->setLanguage('en')
            ->setParams(array(''))
            ->setCc(array())
            ->setBcc(array());
    }
}
