<?php

namespace NotificationChannels\Lox24\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notification;
use \Mockery;
use NotificationChannels\Lox24\Lox24;
use NotificationChannels\Lox24\Lox24Channel;
use NotificationChannels\Lox24\Lox24Message;

class Lox24ChannelTest extends \PHPUnit_Framework_TestCase
{
    public $responseSuccess = '<?xml version="1.0" encoding="iso-8859-1" ?><answer>
                                 <code>100</code>
                                 <codetext>SMS erfolgreich versendet</codetext>
                                 <info>
                                 <MSGID>e4da3b7fbbce2345d7772b0674a318d5</MSGID>
                                 <Text>Testtext</Text>
                                 <Zeichen>8</Zeichen>
                                 <SMS>1</SMS>
                                 <Absenderkennung>0049(160)123456</Absenderkennung>
                                 <Ziel>+49(160)654321</Ziel>
                                 <Kosten>0,040</Kosten>
                                 <Versenden>Sofort</Versenden>
                                 </info></answer>';

    public $responseError = '<?xml version="1.0" encoding="iso-8859-1" ?><answer>
                                 <code>200</code>
                                 <codetext>SMS erfolgreich versendet</codetext>
                                 <info>
                                 <MSGID>e4da3b7fbbce2345d7772b0674a318d5</MSGID>
                                 <Text>Testtext</Text>
                                 <Zeichen>8</Zeichen>
                                 <SMS>1</SMS>
                                 <Absenderkennung>0049(160)123456</Absenderkennung>
                                 <Ziel>+49(160)654321</Ziel>
                                 <Kosten>0,040</Kosten>
                                 <Versenden>Sofort</Versenden>
                                 </info></answer>';

    public function tearDown()
    {
        Mockery::close();
    }


    /** @test */
    public function canSendNotification()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $this->responseSuccess)
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);


        $lox24 = new Lox24('123456', 123456, null, $client);

        $channel = new Lox24Channel($lox24);
        $channel->send(new TestNotifiable(), new TestNotification());
    }

}


class TestNotifiable
{
    public function routeNotificationFor()
    {
        return '';
    }
}
class TestNotification extends Notification
{
    public function toLox24()
    {
        return Lox24Message::create('hello')->setTo('12345789')->setFrom('lox24test');
    }
}

