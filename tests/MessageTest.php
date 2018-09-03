<?php

namespace NotificationChannels\ExpoPushNotifications\Test;

use Illuminate\Support\Arr;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;

class MessageTest extends TestCase
{
    /**
     * @var ExpoMessage
     */
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->message = new ExpoMessage();
    }

    /** @test */
    public function itProvidesACreateMethod()
    {
        $message = ExpoMessage::create('myMessage');

        $this->assertEquals('myMessage', Arr::get($message->toArray(), 'body'));
    }

    /** @test */
    public function itCanAcceptsABodyWhenConstructingAMessage()
    {
        $message = new ExpoMessage('myMessage');

        $this->assertEquals('myMessage', Arr::get($message->toArray(), 'body'));
    }

    /** @test */
    public function itProvidesACreateMethodThatAcceptsAMessageBody()
    {
        $message = new ExpoMessage('myMessage');

        $this->assertEquals('myMessage', Arr::get($message->toArray(), 'body'));
    }

    /** @test */
    public function itSetsABodyToTheMessage()
    {
        $this->message->body('myMessage');

        $this->assertEquals('myMessage', Arr::get($this->message->toArray(), 'body'));
    }

    /** @test */
    public function itSetsADefaultSound()
    {
        $this->assertEquals('default', Arr::get($this->message->toArray(), 'sound'));
    }

    /** @test */
    public function itCanMuteSound()
    {
        $this->message->disableSound();

        $this->assertNull(Arr::get($this->message->toArray(), 'sound'));
    }

    /** @test */
    public function itCanEnableSound()
    {
        $this->message->disableSound();
        $this->message->enableSound();

        $this->assertNotNull(Arr::get($this->message->toArray(), 'sound'));
    }

    /** @test */
    public function itCanSetTheBadge()
    {
        $this->message->badge(5);

        $this->assertEquals(5, Arr::get($this->message->toArray(), 'badge'));
    }

    /** @test */
    public function itCanSetTimeToLive()
    {
        $this->message->setTtl(60);

        $this->assertEquals(60, Arr::get($this->message->toArray(), 'ttl'));
    }

    /** @test */
    public function itCanSetChannelId()
    {
        $this->message->setChannelId('some-channel');

        $this->assertEquals('some-channel', Arr::get($this->message->toArray(), 'channelId'));
    }

    /** @test */
    public function itCanSetJSONData()
    {
        $this->message->setJsonData('{"name":"Aly"}');

        $this->assertEquals('{"name":"Aly"}', Arr::get($this->message->toArray(), 'data'));
    }
}
