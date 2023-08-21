<?php

namespace NotificationChannels\LaravelZenviaChannel\Tests\Unit;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\LaravelZenviaChannel\Enums\CallbackOptionEnum;
use NotificationChannels\LaravelZenviaChannel\ZenviaSmsMessage;

class ZenviaSmsMessageTest extends MockeryTestCase
{
    /** @var ZenviaSmsMessage */
    protected $message;

    public function setUp(): void
    {
        $this->message = new ZenviaSmsMessage();
    }

    /** @test */
    public function it_can_accept_a_message_when_constructing_a_message()
    {
        $message = new ZenviaSmsMessage('myMessage');

        $this->assertEquals('myMessage', $message->content);
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = ZenviaSmsMessage::create('myMessage');

        $this->assertEquals('myMessage', $message->content);
    }

    /** @test */
    public function it_can_set_optional_parameters()
    {
        $message = ZenviaSmsMessage::create('myMessage');
        $message->schedule('2023-08-20T02:01:23');
        $message->callbackOption(CallbackOptionEnum::OPTION_ALL);
        $message->id('1234');
        $message->aggregateId('ABCD1234');
        $message->flashSms(true);

        $this->assertEquals('2023-08-20T02:01:23', $message->schedule);
        $this->assertEquals(CallbackOptionEnum::OPTION_ALL, $message->callbackOption);
        $this->assertEquals('1234', $message->id);
        $this->assertEquals('ABCD1234', $message->aggregateId);
        $this->assertEquals(true, $message->flashSms);
    }
}
