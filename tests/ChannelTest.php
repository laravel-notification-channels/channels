<?php

namespace NotificationChannels\Pushmix\Test;

use Mockery;
use Orchestra\Testbench\TestCase;
use NotificationChannels\Pushmix\PushmixClient;
use NotificationChannels\Pushmix\PushmixChannel;
use Illuminate\Notifications\AnonymousNotifiable;
use NotificationChannels\Pushmix\Exceptions\CouldNotSendNotification;

class ChannelTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        //setup db config if needed
        $app['config']->set('services.pushmix.key', 'SUBSCRIPTION_ID');
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_send()
    {
        $client = new PushmixClient();
        $client->setApiUrl('https://www.pushmix.co.uk/api/ping');
        $channel = new PushmixChannel($client);
        $notifiable = (new AnonymousNotifiable)->route('Pushmix', 'all');
        $response = $channel->send($notifiable, new OrderShipped());

        $this->assertSame(200, $response->getStatusCode());
    }

    /***/

    /** @test */
    public function it_can_throw_exception()
    {
        $this->expectException(CouldNotSendNotification::class);
        $client = new PushmixClient();
        $client->setApiUrl('https://www.pushmix.co.uk/api/fail');
        $channel = new PushmixChannel($client);
        $notifiable = (new AnonymousNotifiable)->route('Pushmix', 'all');
        $channel->send($notifiable, new OrderShipped());
    }

    /***/

    /** @test */
    public function it_can_route_notification()
    {
        $client = new PushmixClient();
        $client->setApiUrl('https://www.pushmix.co.uk/api/ping');
        $channel = new PushmixChannel($client);
        $notifiable = (new AnonymousNotifiable)->route('Not_Pushmix', 'all');
        $channel->send($notifiable, new OrderShipped());
        $this->assertTrue(true);
    }

    /***/
}
