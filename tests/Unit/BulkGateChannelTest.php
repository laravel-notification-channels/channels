<?php

declare(strict_types=1);

namespace NotificationChannels\BulkGate\Test\Unit;

use BulkGate\Message\Response;
use BulkGate\Sms\Sender;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notifiable;
use Mockery;
use NotificationChannels\BulkGate\BulkGateChannel;
use NotificationChannels\BulkGate\Events\BulkGateSmsSent;
use NotificationChannels\BulkGate\Exceptions\CouldNotSendNotification;
use NotificationChannels\BulkGate\Test\Fixtures\InvalidMessageTestNotification;
use NotificationChannels\BulkGate\Test\Fixtures\MissingRouteTestNotification;
use NotificationChannels\BulkGate\Test\Fixtures\TestNotification;
use NotificationChannels\BulkGate\Test\Fixtures\TestNotificationStaticCreate;
use NotificationChannels\BulkGate\Test\Fixtures\TestNotificationWithCustomRecipient;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

/**
 * @internal
 * @coversNothing
 */
class BulkGateChannelTest extends TestbenchTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->sender = Mockery::mock(Sender::class);
        $this->response = Mockery::mock(Response::class);
    }

    public function testSuccessSend()
    {
        $this->sender->shouldReceive('send')
            ->withArgs(function ($message) {
                return '71234567890' === $message->getPhoneNumber()->getPhoneNumber();
            })
            ->andReturn($this->response);

        $this->response->shouldReceive('isSuccess')
            ->andReturn(true);

        $this->dispatcher->shouldReceive('dispatch')
            ->with(BulkGateSmsSent::class);

        $channel = new BulkGateChannel($this->sender, $this->dispatcher);
        $channel->send(new NotifiableWithAttribute(), new TestNotificationStaticCreate());
    }

    public function testNonStaticMessageCreateSend()
    {
        $this->sender->shouldReceive('send')
            ->withArgs(function ($message) {
                return '71234567890' === $message->getPhoneNumber()->getPhoneNumber();
            })
            ->andReturn($this->response);

        $this->response->shouldReceive('isSuccess')
            ->andReturn(true);

        $this->dispatcher->shouldReceive('dispatch')
            ->with(BulkGateSmsSent::class);

        $channel = new BulkGateChannel($this->sender, $this->dispatcher);
        $channel->send(new NotifiableWithAttribute(), new TestNotification());
    }

    public function testSendToNotifiableWithRoute()
    {
        $this->sender->shouldReceive('send')
            ->withArgs(function ($message) {
                return '71234567890' === $message->getPhoneNumber()->getPhoneNumber();
            })
            ->andReturn($this->response);

        $this->response->shouldReceive('isSuccess')
            ->andReturn(true);

        $this->dispatcher->shouldReceive('dispatch')
            ->with(BulkGateSmsSent::class);

        $channel = new BulkGateChannel($this->sender, $this->dispatcher);
        $channel->send(new NotifiableWithRoute(), new TestNotificationStaticCreate());
    }

    public function testSendToCustomRecipient()
    {
        $this->sender->shouldReceive('send')
            ->withArgs(function ($message) {
                return TestNotificationWithCustomRecipient::RECIPIENT === $message->getPhoneNumber()->getPhoneNumber();
            })
            ->andReturn($this->response);

        $this->response->shouldReceive('isSuccess')
            ->andReturn(true);

        $this->dispatcher->shouldReceive('dispatch')
            ->with(BulkGateSmsSent::class);

        $channel = new BulkGateChannel($this->sender, $this->dispatcher);
        $channel->send(new NotifiableWithAttribute(), new TestNotificationWithCustomRecipient());
    }

    public function testRiseExceptionWhenInvalidMessage()
    {
        $this->dispatcher->shouldReceive('dispatch')
            ->with(NotificationFailed::class);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessageMatches('/^Message must be an instance of/');

        $channel = new BulkGateChannel($this->sender, $this->dispatcher);
        $channel->send(new NotifiableWithAttribute(), new InvalidMessageTestNotification());
    }

    public function testRiseExceptionWhenNotificationHasNoToBulkGateRoute()
    {
        $this->dispatcher->shouldReceive('dispatch')
            ->with(NotificationFailed::class);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessageMatches('/method in order to send via the BulkGate SMS channel\.$/');

        $channel = new BulkGateChannel($this->sender, $this->dispatcher);
        $channel->send(new NotifiableWithAttribute(), new MissingRouteTestNotification());
    }

    public function testRiseExceptionWhenInvalidResponse()
    {
        $this->dispatcher->shouldReceive('dispatch')
            ->with(NotificationFailed::class);

        $this->sender->shouldReceive('send')
            ->andReturn($this->response);

        $this->response->shouldReceive('isSuccess')
            ->andReturn(false);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessageMatches('/^BulkGate responded with an error:/');

        $channel = new BulkGateChannel($this->sender, $this->dispatcher);
        $channel->send(new NotifiableWithAttribute(), new TestNotificationStaticCreate());
    }
}

class NotifiableWithAttribute
{
    use Notifiable;
    public $phone_number = '+71234567890';
}

class NotifiableWithRoute
{
    use Notifiable;

    public function routeNotificationForBulkGate()
    {
        return '+71234567890';
    }
}
