<?php

namespace NotificationChannels\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\Exceptions\CouldNotSendNotification;
use NotificationChannels\HangoutsChatChannel;
use NotificationChannels\HangoutsChatMessage;
use PHPUnit\Framework\TestCase;

class ChannelTest extends TestCase
{
    /** @test */
    public function it_can_send_a_notification()
    {
        $response = new Response(200);
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
            ->once()
            ->with(
                'https://chat.googleapis.com/v1/spaces/XXXXX-XXXXX/messages',
                [
                    'query' => null,
                    'body' => '{"text":"It is a test message!"}',
                    'verify' => false,
                    'headers' => [
                        'User-Agent' => 'WebhookAgent',
                        'X-Custom' => 'CustomHeader',
                    ],
                ]
            )
            ->andReturn($response);
        $channel = new HangoutsChatChannel($client);
        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_can_send_a_notification_with_2xx_status()
    {
        $response = new Response(201);
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
            ->once()
            ->with(
                'https://chat.googleapis.com/v1/spaces/XXXXX-XXXXX/messages',
                [
                    'query' => null,
                    'body' => '{"text":"It is a test message!"}',
                    'verify' => false,
                    'headers' => [
                        'User-Agent' => 'WebhookAgent',
                        'X-Custom' => 'CustomHeader',
                    ],
                ]
            )
            ->andReturn($response);
        $channel = new HangoutsChatChannel($client);
        $channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_can_send_a_notification_with_query_string()
    {
        $response = new Response();
        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
            ->once()
            ->with(
                'https://chat.googleapis.com/v1/spaces/XXXXX-XXXXX/messages',
                [
                    'query' => [
                        'key' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
                        'token' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
                    ],
                    'body' => '""',
                    'verify' => false,
                    'headers' => [
                        'User-Agent' => 'WebhookAgent',
                        'X-Custom' => 'CustomHeader',
                    ],
                ]
            )
            ->andReturn($response);

        $channel = new HangoutsChatChannel($client);
        $channel->send(new TestNotifiable(), new QueryTestNotification());
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_it_could_not_send_the_notification()
    {
        $response = new Response(500);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Google Hangouts Chat API responded with an error: ``');
        $this->expectExceptionCode(500);

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('post')
            ->once()
            ->andReturn($response);
        $channel = new HangoutsChatChannel($client);
        $channel->send(new TestNotifiable(), new TestNotification());
    }
}

class TestNotifiable
{
    use Notifiable;

    /**
     * @return int
     */
    public function routeNotificationForHangoutsChat()
    {
        return 'https://chat.googleapis.com/v1/spaces/XXXXX-XXXXX/messages';
    }
}

class TestNotification extends Notification
{
    public function toHangoutsChat($notifiable)
    {
        return
            (new HangoutsChatMessage(
                [
                    'text' => 'It is a test message!',
                ]
            ))->userAgent('WebhookAgent')
                ->header('X-Custom', 'CustomHeader');
    }
}

class QueryTestNotification extends Notification
{
    public function toHangoutsChat($notifiable)
    {
        return
            (new HangoutsChatMessage())
                ->query([
                    'key' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
                    'token' => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
                ])
                ->userAgent('WebhookAgent')
                ->header('X-Custom', 'CustomHeader');
    }
}
