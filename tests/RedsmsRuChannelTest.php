<?php

namespace NotificationChannel\RedsmsRu\Tests;

use Mockery as M;
use Illuminate\Notifications\Notification;
use NotificationChannels\RedsmsRu\RedsmsRuApi;
use NotificationChannels\RedsmsRu\RedsmsRuChannel;
use NotificationChannels\RedsmsRu\RedsmsRuMessage;
use NotificationChannels\RedsmsRu\Exceptions\CouldNotSendNotification;

class RedsmsRuChannelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RedsmsRuApi
     */
    private $api;

    /**
     * @var RedsmsRuMessage
     */
    private $message;

    /**
     * @var RedsmsRuChannel
     */
    private $channel;

    public function setUp()
    {
        parent::setUp();

        $this->api = M::mock(RedsmsRuApi::class, ['test', 'test', 'John_Doe']);
        $this->channel = new RedsmsRuChannel($this->api);
        $this->message = M::mock(RedsmsRuMessage::class);
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->api->shouldReceive('send')->once()
            ->with(
                [
                    'phone' => '1234567890',
                    'text' => 'hello',
                    'sender' => 'John_Doe',
                ]
            );

        $this->channel->send(new TestNotifiable, new TestNotification);
    }

    /** @test */
    public function it_does_not_send_a_message_when_to_missed()
    {
        $this->expectException(CouldNotSendNotification::class);

        $this->channel->send(
            new TestNotifiableWithoutRouteNotificationForRedsmsru, new TestNotification
        );
    }
}

class TestNotifiable
{
    public function routeNotificationFor()
    {
        return '1234567890';
    }
}

class TestNotifiableWithoutRouteNotificationForRedsmsru extends TestNotifiable
{
    public function routeNotificationFor()
    {
        return false;
    }
}

class TestNotification extends Notification
{
    public function toRedsmsRu()
    {
        return new RedsmsRuMessage('hello');
    }
}
