<?php

namespace NotificationChannels\SMS77\Test;

use Mockery;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use NotificationChannels\SMS77\SMS77;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use NotificationChannels\SMS77\SMS77Channel;
use NotificationChannels\SMS77\SMS77Message;

/**
 * Class SMS77ChannelTest.
 */
class SMS77MessageTest extends TestCase
{
    public function testPassMessageToConstructor()
    {
        $message = new SMS77Message('This is my message.');
        $this->assertEquals('This is my message.', $message->getPayloadValue('text'));
    }

    public function testCreateMessageWithCreateMethod()
    {
        $message = SMS77Message::create('This is my message.');
        $this->assertEquals('This is my message.', $message->getPayloadValue('text'));
    }

    public function testMessageCanBeSet()
    {
        $message = new SMS77Message();
        $message->content('This is my message.');
        $this->assertEquals('This is my message.', $message->getPayloadValue('text'));
    }

    public function testApiJsonResponseIsEnabledByDefault()
    {
        $message = new SMS77Message('This is my message.');
        $this->assertEquals(1, $message->getPayloadValue('json'));
    }

    public function testMessageCanReturnPayloadAsArray()
    {
        $message = (new SMS77Message('This is my message.'))
            ->debug()
            ->from('SMS')
            ->to('123456789')
            ->flash();

        $expected = [
            'json' => 1,
            'from' => 'SMS',
            'to' => '123456789',
            'text' => 'This is my message.',
            'flash' => 1,
            'debug' => 1
        ];

        $this->assertEquals($expected, $message->toArray());
    }
}
