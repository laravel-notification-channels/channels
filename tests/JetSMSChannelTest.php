<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016
 * Time: 22:17.
 */
namespace NotificationChannels\JetSMS\Test;

use Mockery as M;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use NotificationChannels\JetSMS\JetSMSChannel;
use NotificationChannels\JetSMS\JetSMSMessage;
use NotificationChannels\JetSMS\JetSMSMessageInterface;
use NotificationChannels\JetSMS\Clients\JetSMSClientInterface;
use NotificationChannels\JetSMS\Clients\JetSMSApiResponseInterface;
use NotificationChannels\JetSMS\Exceptions\CouldNotSendNotification;

class JetSMSChannelTest extends \PHPUnit_Framework_TestCase
{
    private $message;
    private $client;
    private $channel;
    private $apiResponse;
    private $eventDispatcher;

    public function setUp()
    {
        parent::setUp();

        $this->client = M::mock(JetSMSClientInterface::class);
        $this->eventDispatcher = M::mock(Dispatcher::class);
        $this->channel = new JetSMSChannel($this->client, $this->eventDispatcher);
        $this->message = M::mock(JetSMSMessageInterface::class);
        $this->apiResponse = M::mock(JetSMSApiResponseInterface::class);
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    /** @test */
    public function it_can_send_notification()
    {
        $this->client->shouldReceive('addToRequest')
                     ->once();
        $this->client->shouldReceive('sendRequest')
                     ->once()
                     ->andReturn($this->apiResponse);
        $this->eventDispatcher->shouldReceive('fire')
                              ->twice();

        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_can_send_text_notification()
    {
        $this->client->shouldReceive('addToRequest')
                     ->once();
        $this->client->shouldReceive('sendRequest')
                     ->once()
                     ->andReturn($this->apiResponse);
        $this->eventDispatcher->shouldReceive('fire')
                              ->twice();

        $this->channel->send(new TestNotifiable(), new TestNotificationWithString());
    }

    /** @test */
    public function it_does_not_send_sms_when_recipient_is_missing()
    {
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->channel->send(new TestNotifiableWithNoRecipients(), new TestNotification());
    }

    /** @test */
    public function it_does_not_send_sms_when_content_is_too_long()
    {
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->channel->send(new TestNotifiable(), new TestNotificationWithTooLongContent());
    }
}

class TestNotifiable
{
    public function routeNotificationFor()
    {
        return '+1234567890';
    }
}

class TestNotifiableWithNoRecipients
{
    public function routeNotificationFor()
    {
    }
}

class TestNotification extends Notification
{
    public function toJetSMS()
    {
        return new JetSMSMessage('hello', 'John_Doe');
    }
}

class TestNotificationWithString extends Notification
{
    public function toJetSMS()
    {
        return 'hello';
    }
}

class TestNotificationWithTooLongContent extends Notification
{
    public function toJetSMS()
    {
        return 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel vestibulum massa. Nulla in condimentum justo. Pellentesque tempus ultrices fringilla. Pellentesque leo metuss.';
    }
}
