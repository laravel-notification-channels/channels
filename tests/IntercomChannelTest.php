<?php
/**
 * @link      http://horoshop.ua
 *
 * @copyright Copyright (c) 2015-2018 Horoshop TM
 * @author    Andrey Telesh <andrey@horoshop.ua>
 */

namespace FtwSoft\NotificationChannels\Intercom\Tests;

use FtwSoft\NotificationChannels\Intercom\Exceptions\InvalidArgumentException;
use FtwSoft\NotificationChannels\Intercom\Exceptions\MessageIsNotCompleteException;
use FtwSoft\NotificationChannels\Intercom\Exceptions\RequestException;
use FtwSoft\NotificationChannels\Intercom\IntercomChannel;
use FtwSoft\NotificationChannels\Intercom\IntercomMessage;
use FtwSoft\NotificationChannels\Intercom\Tests\Mocks\TestNotifiable;
use FtwSoft\NotificationChannels\Intercom\Tests\Mocks\TestNotification;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Notifications\Notification;
use Intercom\IntercomClient;
use Intercom\IntercomMessages;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class IntercomChannelTest extends MockeryTestCase
{
    /**
     * @var \Intercom\IntercomMessages|\Mockery\Mock
     */
    private $intercomMessages;

    /**
     * @var \Intercom\IntercomClient
     */
    private $intercom;

    /**
     * @var \FtwSoft\NotificationChannels\Intercom\IntercomChannel
     */
    private $channel;

    protected function setUp()
    {
        parent::setUp();

        $this->intercom = new IntercomClient(null, null);
        $this->intercomMessages = \Mockery::mock(IntercomMessages::class, $this->intercom);
        $this->intercom->messages = $this->intercomMessages;
        $this->channel = new IntercomChannel($this->intercom);
    }

    public function testItCanSendMessage(): void
    {
        $notification = new TestNotification(
            IntercomMessage::create('Hello World!')
                ->from(123)
                ->toUserId(321)
        );

        $this->intercomMessages->shouldReceive('create')
            ->once()
            ->with([
                'body'         => 'Hello World!',
                'message_type' => 'inapp',
                'from'         => [
                    'type' => 'admin',
                    'id'   => '123',
                ],
                'to'           => [
                    'type' => 'user',
                    'id'   => '321',
                ],
            ]);

        $this->channel->send(new TestNotifiable(), $notification);
        $this->assertPostConditions();
    }

    public function testInThrowsAnExceptionWhenNotificationIsNotAnIntercomNotification()
    {
        $notification = new Notification();

        $this->expectException(InvalidArgumentException::class);
        $this->channel->send(new TestNotifiable(), $notification);
    }

    public function testItThrowsAnExceptionWhenRecipientIsNotProvided()
    {
        $notification = new TestNotification(
            IntercomMessage::create('Hello World!')
                ->from(123)
        );

        $this->expectException(MessageIsNotCompleteException::class);
        $this->channel->send(new TestNotifiable(), $notification);
    }

    public function testItThrowsAnExceptionSomeOfRequiredParamsAreNotDefined()
    {
        $notification = new TestNotification(
            IntercomMessage::create()
                ->from(123)
                ->toUserId(321)
        );

        $this->expectException(MessageIsNotCompleteException::class);
        $this->channel->send(new TestNotifiable(), $notification);
    }

    public function testItThrowsRequestExceptionOnGuzzleBadResponseException(): void
    {
        $this->intercomMessages->shouldReceive('create')
            ->once()
            ->andThrow(new BadResponseException('Test case', new Request('post', 'http://foo.bar')));

        $notification = new TestNotification(
            IntercomMessage::create('Hello World!')
                ->from(123)
                ->toUserId(321)
        );

        $this->expectException(RequestException::class);

        $this->channel->send(new TestNotifiable(), $notification);
    }

    public function testItGetsToFromRouteNotificationForIntercomMethod(): void
    {
        $this->intercomMessages->shouldReceive('create');

        $message = IntercomMessage::create('Hello World!')
            ->from(123);
        $notification = new TestNotification($message);

        $expected = ['type' => 'user', 'id' => 321];
        $this->channel->send(new TestNotifiable($expected), $notification);

        $this->assertEquals($expected, $message->payload['to']);
    }
}
