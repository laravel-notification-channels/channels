<?php

use App\Channels\PushoverMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\PushoverNotifications\Channel;
use NotificationChannels\PushoverNotifications\Pushover;
use GuzzleHttp\Client as HttpClient;

class PushoverTest extends PHPUnit_Framework_TestCase
{
    /** @var HttpClient */
    protected $guzzleClient;

    /** @var Notification */
    protected $notification;

    public function setUp()
    {
        parent::setUp();

        $this->guzzleClient = Mockery::mock(HttpClient::class);
        $this->notification = Mockery::mock(Notification::class);

        $this->ignoreEvents();
    }

    /** @test */
    public function it_can_send_a_pushover_notification()
    {
        $message = (new PushoverMessage('Message text'))
            ->title('Message title')
            ->emergencyPriority(60, 600)
            ->time(123456789)
            ->sound('boing')
            ->url('http://example.com', 'Example Website');

        $this->requestWillBeSentToPushoverWith([
            'token' => 'application-token',
            'user' => 'pushover-key',
            'message' => 'Message text',
            'title' => 'Message title',
            'priority' => 2,
            'retry' => 60,
            'expire' => 600,
            'timestamp' => 123456789,
            'sound' => 'boing',
            'url' => 'http://example.com',
            'url_title' => 'Example Website',
        ]);

        $pushover = new Pushover($this->guzzleClient, 'application-token');

        $channel = new Channel($pushover);

        $this->notification->shouldReceive('toPushover')->andReturn($message);

        $channel->send(new Notifiable, $this->notification);
    }

    private function requestWillBeSentToPushoverWith($params)
    {
        $this->guzzleClient->shouldReceive('post')
            ->with('https://api.pushover.net/1/messages.json', [
                'form_params' => $params,
            ]);
    }

    private function ignoreEvents()
    {
        $dispatcher = Mockery::mock('Illuminate\Contracts\Events\Dispatcher');
        $dispatcher->shouldReceive('fire');
        app()->instance('events', $dispatcher);
    }

}


class Notifiable
{
    public function routeNotificationFor($channel)
    {
        return 'pushover-key';
    }
}