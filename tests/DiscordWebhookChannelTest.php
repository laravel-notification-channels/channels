<?php

namespace NotificationChannels\DiscordWebhook\Test;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\DiscordWebhook\DiscordWebhookChannel;
use NotificationChannels\DiscordWebhook\Exceptions\InvalidMessage;
use NotificationChannels\DiscordWebhook\Exceptions\CouldNotSendNotification;

class DiscordWebhookChannelTest extends \PHPUnit_Framework_TestCase
{
    /** @var \GuzzleHttp\Client */
    protected $httpClient;

    /** @var \NotificationChannels\DiscordWebhook\DiscordWebhookChannel */
    protected $channel;

    public function setUp()
    {
        parent::setUp();
        $this->httpClient = Mockery::mock(new Client());
        $this->channel = Mockery::mock(new DiscordWebhookChannel($this->httpClient));
        $this->notifiable = new TestNotifiable();
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_ignore_other_route_notification()
    {
        $this->httpClient->shouldReceive('post')->never();
        $this->channel->send(new TestOtherNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->httpClient->shouldReceive('post')->once()->andReturn(new Response(204));
        $this->channel->send($this->notifiable, new TestNotification());
    }

    /** @test */
    public function it_can_send_notification_and_wait_for_response()
    {
        $this->httpClient->shouldReceive('post')->once()->andReturn(new Response(200, [], '{"json": true}'));
        $this->channel->send($this->notifiable, new TestNotification());
    }

    /** @test */
    public function it_can_send_a_file()
    {
        $this->httpClient->shouldReceive('post')->once()->andReturn(new Response(204));
        $this->channel->send($this->notifiable, new TestFileNotification());
    }

    /** @test */
    public function it_throws_an_exception_when_it_could_not_send_a_notification()
    {
        $exception = new RequestException('Error', new Request('POST', 'webhook_fail'));
        $this->httpClient->shouldReceive('post')->once()->andThrow($exception);
        $this->setExpectedException(CouldNotSendNotification::class);
        $this->channel->send($this->notifiable, new TestNotification());
    }

    /** @test */
    public function it_throws_an_exception_with_response_when_it_could_not_send_a_notification()
    {
        $exception = new ClientException('Error', new Request('POST', 'webhook_fail'), new Response(400));
        $this->httpClient->shouldReceive('post')->once()->andThrow($exception);
        $this->setExpectedException(CouldNotSendNotification::class);
        $this->channel->send($this->notifiable, new TestNotification());
    }

    /** @test */
    public function it_throws_an_exception_when_the_message_is_empty()
    {
        $this->setExpectedException(InvalidMessage::class);
        $this->channel->send($this->notifiable, new TestEmptyNotification());
    }

    /** @test */
    public function it_throws_an_exception_when_message_is_unsupported()
    {
        $this->setExpectedException(InvalidMessage::class);
        $this->channel->send($this->notifiable, new TestUnsupportedNotification());
    }
}
