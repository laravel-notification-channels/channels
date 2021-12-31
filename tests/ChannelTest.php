<?php

namespace NotificationChannels\WXWork\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notification;
use Mockery;
use NotificationChannels\WXWork\WXWorkChannel;
use NotificationChannels\WXWork\WXWorkMessage;
use PHPUnit\Framework\TestCase;

class ChannelTest extends TestCase
{
    /** @test */
    public function it_can_send_a_makrdown_notification()
    {
        $response = new Response(200, ['Error-Msg'=>'ok', 'Error-Code'=>'0']);
        $client = Mockery::mock(Client::class);
        $message = (new WXWorkMessage('foobar'))->toMarkdown();
        $client->shouldReceive('post')->once()->with(
                'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=Test_Token',
                [
                    'body' => $message,
                ]
            )->andReturn($response);
        $channel = new WXWorkChannel($client, 'Test_Token');
        $actualResponse = $channel->send(new TestNotifiable(), new TestMarkdownNotification());
        self::assertSame($response, $actualResponse);
    }

    /** @test */
    public function it_can_send_a_text_notification()
    {
        $response = new Response(200, ['Error-Msg'=>'ok', 'Error-Code'=>'0']);
        $client = Mockery::mock(Client::class);
        $message = (new WXWorkMessage('foobar'))->toText();
        $client->shouldReceive('post')->once()->with(
                'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=Test_Token',
                [
                    'body' => $message,
                ]
            )->andReturn($response);
        $channel = new WXWorkChannel($client, 'Test_Token');
        $actualResponse = $channel->send(new TestNotifiable(), new TestTextNotification());

        self::assertSame($response, $actualResponse);
    }
}

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    /**
     * @return int
     */
    public function routeNotificationForWXWork()
    {
        return 'Test_Token';
    }
}

class TestTextNotification extends Notification
{
    public function toWXWork($notifiable)
    {
        return (new WXWorkMessage('foobar'))->ToText();
    }
}

class TestMarkdownNotification extends Notification
{
    public function toWXWork($notifiable)
    {
        return (new WXWorkMessage('foobar'))->ToMarkDown();
    }
}
