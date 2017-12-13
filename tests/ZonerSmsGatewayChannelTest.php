<?php
/**
 * Created by PhpStorm.
 * User: jarno
 * Date: 12.12.2017
 * Time: 9.38.
 */

namespace NotificationChannels\ZonerSmsGateway\Test;

use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use NotificationChannels\ZonerSmsGateway\ZonerSmsGateway;
use NotificationChannels\ZonerSmsGateway\ZonerSmsGatewayChannel;
use NotificationChannels\ZonerSmsGateway\ZonerSmsGatewayMessage;

class ZonerSmsGatewayChannelTest extends TestCase
{
    /** @var array */
    protected $transactions = [];
    /** @var ZonerSmsGatewayChannel */
    protected $channel;
    /** @var Notifiable */
    protected $notifiable;
    /** @var Notification */
    protected $notification;

    private function setUpWithResponses($responses)
    {
        // Create mock handler for GuzzleHttp:
        $mockHttpHandler = new MockHandler($responses);
        $handler = HandlerStack::create($mockHttpHandler);

        // Remember the HTTP requests:
        $history = Middleware::history($this->transactions);
        $handler->push($history);

        $this->notifiable = new NotifiableWithRouting();
        $this->notification = new NotificationThatDoesNotDefineReceiverInMessage();

        $httpClient = new HttpClient(['handler' => $handler]);
        $gateway = new ZonerSmsGateway('myuser', 'mypass', 'default-sender', $httpClient);
        $this->channel = new ZonerSmsGatewayChannel($gateway);
    }

    /**
     * @test
     */
    public function sendsUsernameAndPasswordParametersInRequest()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'OK 1231234'),
        ]);

        $this->channel->send($this->notifiable, $this->notification);

        $this->assertCount(1, $this->transactions);

        $transaction = $this->transactions[0];
        $request = $transaction['request'];

        $this->assertRegExp('/username=myuser/', (string) $request->getBody());
        $this->assertRegExp('/password=mypass/', (string) $request->getBody());
    }

    /**
     * @test
     */
    public function sendsMessageParameterInRequest()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'OK 1231234')
        ]);

        $this->channel->send($this->notifiable, $this->notification);

        $transaction = $this->transactions[0];
        $request = $transaction['request'];

        $this->assertRegExp('/message=hello\+zoner/', (string) $request->getBody());
    }

    /**
     * @test
     */
    public function usesSenderFromMessageWhenSet()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'OK 1231234')
        ]);

        $notification = new NotificationThatDefinesSenderInMessage();
        $this->channel->send($this->notifiable, $notification);

        $transaction = $this->transactions[0];
        $request = $transaction['request'];

        $this->assertRegExp('/numberfrom=sender-from-message/', (string) $request->getBody());
    }

    /**
     * @test
     */
    public function usesDefaultSenderIfNotSetInMessage()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'OK 1231234')
        ]);

        $notification = new NotificationThatDoesNotDefineSenderInMessage();
        $this->channel->send($this->notifiable, $notification);

        $transaction = $this->transactions[0];
        $request = $transaction['request'];

        $this->assertRegExp('/numberfrom=default-sender/', (string) $request->getBody());
    }

    /**
     * @test
     */
    public function usesReceiverFromMessageWhenSet()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'OK 1231234')
        ]);

        $notification = new NotificationThatDefinesReceiverInMessage();
        $this->channel->send($this->notifiable, $notification);

        $transaction = $this->transactions[0];
        $request = $transaction['request'];

        $this->assertRegExp('/numberto=receiver-from-message/', (string) $request->getBody());
    }

    /**
     * @test
     */
    public function usesReceiverFromNotifiableRoutingWhenNotSetInMessage()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'OK 1231234')
        ]);

        $notifiable = new NotifiableWithRouting();
        $this->channel->send($notifiable, $this->notification);

        $transaction = $this->transactions[0];
        $request = $transaction['request'];

        $this->assertRegExp('/numberto=receiver-from-notifiable-routing/', (string) $request->getBody());
    }

    /**
     * @test
     */
    public function usesReceiverFromNotifiablePhoneNumberWhenRoutingNotDefined()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'OK 1231234')
        ]);

        $notifiable = new NotifiableWithPhoneNumber();
        $this->channel->send($notifiable, $this->notification);

        $transaction = $this->transactions[0];
        $request = $transaction['request'];

        $this->assertRegExp('/numberto=receiver-from-notifiable-phone-number/', (string) $request->getBody());
    }

    /**
     * @test
     */
    public function returnsTrackingCodeOnSuccessfulSend()
    {
        $this->setUpWithResponses([
            new Response(200, [], 'OK 1231234'),
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
            new Response(200, [], 'ERR -123'),
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
            new Response(500, []),
        ]);

        $this->channel->send($this->notifiable, $this->notification);
    }
}

class NotifiableWithRouting {
    use Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForZonerSmsGateway()
    {
        return 'receiver-from-notifiable-routing'; // Should be a phone number in reality
    }
}

class NotifiableWithPhoneNumber {
    use Notifiable;

    public $phone_number = 'receiver-from-notifiable-phone-number'; // Should be a phone number in reality
}

class NotificationThatDefinesSenderInMessage extends Notification
{
    public function toZonerSmsGateway($notifiable)
    {
        return (new ZonerSmsGatewayMessage())
            ->content('hello zoner')
            ->sender('sender-from-message');
    }
}

class NotificationThatDoesNotDefineSenderInMessage extends Notification
{
    public function toZonerSmsGateway($notifiable)
    {
        return (new ZonerSmsGatewayMessage())
            ->content('hello zoner');
    }
}

class NotificationThatDefinesReceiverInMessage extends Notification
{
    public function toZonerSmsGateway($notifiable)
    {
        return (new ZonerSmsGatewayMessage())
            ->content('hello zoner')
            ->receiver('receiver-from-message');
    }
}

class NotificationThatDoesNotDefineReceiverInMessage extends Notification
{
    private $sender;

    public function toZonerSmsGateway($notifiable)
    {
        return (new ZonerSmsGatewayMessage())
            ->content('hello zoner');
    }
}
