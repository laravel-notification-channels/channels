<?php

namespace NotificationChannels\NextcloudTalk\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\NextcloudTalk\Exceptions\CouldNotSendNotification;
use NotificationChannels\NextcloudTalk\NextcloudTalk;
use NotificationChannels\NextcloudTalk\NextcloudTalkChannel;
use NotificationChannels\NextcloudTalk\NextcloudTalkMessage;
use PHPUnit\Framework\TestCase;

class NextcloudTalkChannelTest extends TestCase
{
    protected $nextcloudTalk;
    protected $client;

    protected $baseUrl = 'http://localhost';
    protected $basePath = '/ocs/v2.php/apps/spreed/api';
    protected $defaultChannel = 'defaultChannel';
    protected $onetooneChannel = 'onetoone';

    public function setUp(): void
    {
        parent::setUp();
        $this->client = Mockery::mock(Client::class);
        $this->nextcloudTalk = new NextcloudTalk($this->client, $this->defaultChannel, $this->onetooneChannel);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_can_send_notification_to_user(): void
    {
        $user = 'max';
        $token = 'usertoken';

        $this->client
        ->shouldReceive('post')
        ->once()
        ->with(
            $this->basePath.'/v4/room',
            [
                'json' => [
                    'roomType' => 1,
                    'invite' => $user,
                    'roomName' => $this->onetooneChannel,
                ],
            ]
        )
            ->andReturn(new Response(
                200,
                [],
                json_encode(['ocs' => ['data' => ['token' => $token]]])
            ));

        $this->client
        ->shouldReceive('post')
        ->once()
        ->with($this->basePath.'/v1/chat/'.$token, [
            'json' => ['message' => 'hello'],
        ])
        ->andReturn(new Response(200));

        $channel = new NextcloudTalkChannel($this->nextcloudTalk);
        $response = $channel->send(new TestNotifiable(), new TestNotificationToUser());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_it_can_send_notification_to_specified_channel(): void
    {
        $this->client
        ->shouldReceive('post')
        ->once()
        ->with($this->basePath.'/v1/chat/channel', [
            'json' => ['message' => 'hello'],
        ])
        ->andReturn(new Response(200));

        $channel = new NextcloudTalkChannel($this->nextcloudTalk);
        $response = $channel->send(new TestNotifiableWithNoRoute(), new TestNotificationToChannel());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_it_can_send_notification_to_default_channel(): void
    {
        $this->client
        ->shouldReceive('post')
        ->once()
        ->with($this->basePath.'/v1/chat/'.$this->defaultChannel, [
            'json' => ['message' => 'hello'],
        ])
        ->andReturn(new Response(200));

        $channel = new NextcloudTalkChannel($this->nextcloudTalk);
        $response = $channel->send(new TestNotifiableWithNoRoute(), new TestNotificationToDefaultChannel());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_handles_channel_creation_api_errors(): void
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->client
        ->shouldReceive('post')
        ->once()
        ->with(
            $this->basePath.'/v4/room',
            Mockery::any()
        )
        ->andThrow(new \GuzzleHttp\Exception\ClientException(
            'Test error',
            new Request('POST', $this->basePath.'/v4/room'),
            new Response(404)
        ));

        $channel = new NextcloudTalkChannel($this->nextcloudTalk);
        $channel->send(new TestNotifiable(), new TestNotificationToUser());
    }

    public function test_handles_message_api_errors(): void
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->client
        ->shouldReceive('post')
        ->once()

        ->with($this->basePath.'/v1/chat/channel', Mockery::any())
        ->andThrow(new \GuzzleHttp\Exception\ClientException(
            'Test error',
            new Request('POST', $this->basePath.'/v1/chat/channel'),
            new Response(404)
        ));

        $channel = new NextcloudTalkChannel($this->nextcloudTalk);
        $channel->send(new TestNotifiable(), new TestNotificationToChannel());
    }

    public function test_throws_error_if_unable_to_find_a_channel_to_send(): void
    {
        $this->expectException(CouldNotSendNotification::class);
        $channel = new NextcloudTalkChannel(new NextcloudTalk($this->client, '', $this->onetooneChannel));
        $channel->send(new TestNotifiableWithNoRoute(), new TestNotificationToUser());
    }

    public function test_throws_error_if_message_is_not_correct_instance(): void
    {
        $this->expectException(CouldNotSendNotification::class);
        $channel = new NextcloudTalkChannel(new NextcloudTalk($this->client, '', $this->onetooneChannel));
        $channel->send(new TestNotifiableWithNoRoute(), new TestNotificationWithWrongMessageType());
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForNextcloudTalk($notification): string
    {
        return 'max';
    }
}

class TestNotifiableWithNoRoute
{
    use Notifiable;
}

class TestNotificationToChannel extends Notification
{
    public function toNextcloudTalk(): NextcloudTalkMessage
    {
        return NextcloudTalkMessage::create('hello')->to('channel');
    }
}

class TestNotificationToUser extends Notification
{
    public function toNextcloudTalk(): NextcloudTalkMessage
    {
        return NextcloudTalkMessage::create('hello');
    }
}

class TestNotificationToDefaultChannel extends Notification
{
    public function toNextcloudTalk(): NextcloudTalkMessage
    {
        return NextcloudTalkMessage::create('hello');
    }
}

class TestNotificationWithWrongMessageType extends Notification
{
    public function toNextcloudTalk()
    {
        return 'Hi';
    }
}
