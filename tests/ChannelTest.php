<?php

namespace NotificationChannels\Chatwork\Test;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Orchestra\Testbench\TestCase;
use Illuminate\Notifications\Notification;
use NotificationChannels\Chatwork\Chatwork;
use NotificationChannels\Chatwork\ChatworkChannel;
use NotificationChannels\Chatwork\ChatworkMessage;
use NotificationChannels\Chatwork\ChatworkInformation;

class ChannelTest extends TestCase
{
    /** @test */
    public function it_can_send_a_notification_message()
    {
        $this->app['config']->set('services.chatwork.api_token', 'API_TOKEN');
        $response = new Response(201);
        $guzzle = Mockery::mock(new Client());
        $client = Mockery::mock(new Chatwork('TOKEN', $guzzle));
        $channel = new ChatworkChannel($client);
        $channel->send(new TestNotifiable(), new TestNotificationChatworkMessage());
    }

    /** @test */
    public function it_can_send_a_notification_information()
    {
        $this->app['config']->set('services.chatwork.api_token', 'API_TOKEN');
        $response = new Response(201);
        $guzzle = Mockery::mock(new Client());
        $client = Mockery::mock(new Chatwork('TOKEN', $guzzle));
        $channel = new ChatworkChannel($client);
        $channel->send(new TestNotifiable(), new TestNotificationChatworkInformation());
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForChatwork()
    {
        return '999999';
    }
}

class TestNotificationChatworkMessage extends Notification
{
    public function toChatwork($notifiable)
    {
        return ChatworkMessage::create('Message');
    }
}

class TestNotificationChatworkInformation extends Notification
{
    public function toChatwork($notifiable)
    {
        return ChatworkInformation::create('Title', 'Message');
    }
}
