<?php

namespace NotificationChannels\Expo\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use NotificationChannels\Expo\ExpoChannel;

class ExpoChannelTest extends TestCase
{

    /** @test */
    public function can_send_notification_to_notifible()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"data":{"status":"ok","id":"0b1c6dbf-77c9-4b9a-b037-33cc8b297c9a"}}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handlerStack]);

        $channel = new ExpoChannel($guzzle);

        $response = $channel->send(new Notifiable(), new TestNotification());
        $json = json_decode($response->getBody());
        $this->assertTrue($json->data->status === 'ok');
    }

    /** @test */
    public function can_send_notification_to_notifible_array()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"data":[{"id":"e74c5f71-c9f0-4dcb-95b9-3dd6bc8d4b0f","status":"ok"},{"status":"ok","id":"d46deba0-1b77-482e-bb29-af2331b7b8d8"}]}'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new Client(['handler' => $handlerStack]);

        $channel = new ExpoChannel($guzzle);

        $response = $channel->send(new Notifiable(), new TestNotification());
        $json = json_decode($response->getBody());
        $this->assertTrue($json->data[0]->status === 'ok');
        $this->assertTrue($json->data[1]->status === 'ok');
    }

}
