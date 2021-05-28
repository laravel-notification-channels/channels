<?php

namespace NotificationChannels\Expo\Test;

use Illuminate\Support\Arr;
use NotificationChannels\Expo\ExpoMessage;

class ExpoMessageTest extends \PHPUnit\Framework\TestCase {
    /** @var \NotificationChannels\Expo\ExpoMessage */
    protected $message;

    public function setUp(): void
    {
        parent::setUp();
        $this->message = new ExpoMessage();
    }

    /** @test */
    public function it_can_accept_a_title_when_constrictung_a_message(){
        $message = new ExpoMessage("Title");
        $this->assertEquals("Title", Arr::get($message->toArray(), 'title'));
    }

    /** @test */
    public function it_can_accept_a_body_when_constrictung_a_message(){
        $message = new ExpoMessage("", "Body");
        $this->assertEquals("Body", Arr::get($message->toArray(), 'body'));
    }

    /** @test */
    public function it_provides_a_create_method(){
        $message = ExpoMessage::create('Title');
        $this->assertEquals("Title", Arr::get($message->toArray(), 'title'));
    }

    /** @test */
    public function it_can_set_the_title(){
        $this->message->title('Title');
        $this->assertEquals("Title", Arr::get($this->message->toArray(), 'title'));
    }

    /** @test */
    public function it_can_set_the_body(){
        $this->message->body('Body');
        $this->assertEquals("Body", Arr::get($this->message->toArray(), 'body'));
    }

    /** @test */
    public function it_can_enable_sound(){
        $this->message->enableSound();
        $this->assertEquals("default", Arr::get($this->message->toArray(), 'sound'));
    }

    /** @test */
    public function it_can_disable_sound(){
        $this->message->disableSound();
        $this->assertEquals(null, Arr::get($this->message->toArray(), 'sound'));
    }

    /** @test */
    public function it_can_set_the_badge(){
        $this->message->badge(9);
        $this->assertEquals(9, Arr::get($this->message->toArray(), 'badge'));
    }

    /** @test */
    public function it_can_set_the_ttl(){
        $this->message->setTtl(50);
        $this->assertEquals(50, Arr::get($this->message->toArray(), 'ttl'));
    }

    /** @test */
    public function it_can_set_the_channelId(){
        $this->message->setChannelId('Channel Name');
        $this->assertEquals('Channel Name', Arr::get($this->message->toArray(), 'channelId'));
    }

    /** @test */
    public function it_can_set_the_json_data_as_array(){
        $this->message->setJsonData(['json' => 'data']);
        $this->assertEquals('{"json":"data"}', Arr::get($this->message->toArray(), 'data'));
    }

    /** @test */
    public function it_can_set_the_json_data_as_string(){
        $this->message->setJsonData('{"json":"string"}');
        $this->assertEquals('{"json":"string"}', Arr::get($this->message->toArray(), 'data'));
    }

    /** @test */
    public function it_can_set_the_priority(){
        $this->message->priority('low');
        $this->assertEquals('low', Arr::get($this->message->toArray(), 'priority'));
    }

}
