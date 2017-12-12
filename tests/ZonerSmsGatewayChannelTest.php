<?php
/**
 * Created by PhpStorm.
 * User: jarno
 * Date: 12.12.2017
 * Time: 9.38
 */

namespace NotificationChannels\ZonerSmsGateway\Test;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use NotificationChannels\ZonerSmsGateway\Exceptions\CouldNotSendNotification;
use NotificationChannels\ZonerSmsGateway\ZonerSmsGateway;
use NotificationChannels\ZonerSmsGateway\ZonerSmsGatewayChannel;
use NotificationChannels\ZonerSmsGateway\ZonerSmsGatewayMessage;
use PHPUnit\Framework\TestCase;

class ZonerSmsGatewayChannelTest extends TestCase
{
    /** @var array */
    protected $transactions = [];
    /** @var  ZonerSmsGatewayChannel */
    protected $channel;
    /** @var  Notifiable */
    protected $notifiable;
    /** @var  Notification */
    protected $notification;

    private function setUpWithResponses($responses) {
        // Create mock handler for GuzzleHttp:
        $mockHttpHandler = new MockHandler($responses);
        $handler = HandlerStack::create($mockHttpHandler);

        // Remember the HTTP requests:
        $history = Middleware::history($this->transactions);
        $handler->push($history);

        $this->notifiable = new TestNotifiable();
        $this->notification = new TestNotification();

        $httpClient = new HttpClient(['handler' => $handler]);
        $gateway = new ZonerSmsGateway('myuser', 'mypass', $httpClient);
        $this->channel = new ZonerSmsGatewayChannel($gateway);
    }

    /**
     * @test
     */
    public function sendsCorrectParametersInRequest()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'OK 1231234')
        ]);

        $this->channel->send($this->notifiable, $this->notification);

        $this->assertCount(1, $this->transactions);

        $transaction = $this->transactions[0];
        $request = $transaction['request'];

        $this->assertRegExp('/username=myuser/', (string) $request->getBody());
        $this->assertRegExp('/password=mypass/', (string) $request->getBody());
        $this->assertRegExp('/numberfrom=zonertest/', (string) $request->getBody());
        $this->assertRegExp('/numberto=112233445566/', (string) $request->getBody());
        $this->assertRegExp('/message=hello\+zoner/', (string) $request->getBody());
    }

    /**
     * @test
     */
    public function returnsTrackingCodeOnSuccessfulSend()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'OK 1231234')
        ]);

        $trackingCode = $this->channel->send($this->notifiable, $this->notification);

        $this->assertEquals('1231234', $trackingCode);
    }

    /**
     * @test
     * @expectedException NotificationChannels\ZonerSmsGateway\Exceptions\CouldNotSendNotification
     * @expectedExceptionCode -123
     */
    public function throwsExceptionOnErrorCodeResponse()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'ERR -123')
        ]);

        $this->channel->send($this->notifiable, $this->notification);
    }

    /**
     * @test
     * @expectedException GuzzleHttp\Exception\ServerException
     * @expectedExceptionCode 500
     */
    public function throwsExceptionOnHttpError()
    {
        $this->setUpWithResponses([
            new Response(500, [])
        ]);

        $this->channel->send($this->notifiable, $this->notification);
    }
}

class TestNotifiable
{
    use Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForZonerSmsGateway()
    {
        return '112233445566';
    }
}

class TestNotification extends Notification
{
    public function toZonerSmsGateway($notifiable)
    {
        return (new ZonerSmsGatewayMessage())
            ->content('hello zoner')
            ->from('zonertest');
    }
}
