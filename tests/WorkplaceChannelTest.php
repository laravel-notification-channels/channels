<?php

namespace NotificationChannels\Workplace\Test;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Notifications\Notification;
use NotificationChannels\Workplace\WorkplaceClient;
use NotificationChannels\Workplace\WorkplaceChannel;
use NotificationChannels\Workplace\WorkplaceMessage;
use NotificationChannels\Workplace\Exceptions\CouldNotSendNotification;

class WorkplaceChannelTest extends TestCase
{
    /** @var Mockery\Mock */
    protected $workplace;

    /** @var \NotificationChannels\Workplace\WorkplaceChannel */
    protected $channel;

    protected $httpHistory = [];
    protected $mockHandler;

    public function setUp(): void
    {
        parent::setUp();
        //$this->workplace = Mockery::mock(WorkplaceClient::class);

        $this->mockHandler = new MockHandler();
        $handler = HandlerStack::create($this->mockHandler);

        $this->httpHistory = [];
        $history = Middleware::history($this->httpHistory);
        // Add the history middleware to the handler stack.
        $handler->push($history);

        $client = new Client(['handler' => $handler]);

        $this->channel = new WorkplaceChannel($client);
    }

    /** @test */
    public function it_can_send_a_message()
    {
        $this->mockHandler->append(new Response(200));
        $this->channel->send(new TestNotifiable(), new TestNotification());

        $this->assertCount(1, $this->httpHistory);

        $request = $this->httpHistory[0]['request'];
        $body = json_decode($request->getBody()->getContents(), true);

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('https://example.com/groupId', $request->getUri()->__toString());
        $this->assertSame(
            [
                'message' => 'Laravel Notification Channels are awesome!',
                'formatting' => 'MARKDOWN',
            ],
            $body
        );
    }

    /** @test */
    public function it_throws_an_exception_when_it_could_not_send_the_notification_because_no_route_notification_defined()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->channel->send(new TestNotifiableNoRouteNotificationDefined(), new TestNotification());
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForWorkplace()
    {
        return 'https://example.com/groupId';
    }
}

class TestNotifiableNoRouteNotificationDefined
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForWorkplace()
    {
        return false;
    }
}

class TestNotification extends Notification
{
    public function toWorkplace($notifiable)
    {
        return new WorkplaceMessage('Laravel Notification Channels are awesome!');
    }
}
