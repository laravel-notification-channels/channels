<?php

namespace NotificationChannels\DiscordWebhook\Test;

use Illuminate\Support\Arr;
use NotificationChannels\DiscordWebhook\DiscordWebhookMessage;

class DiscordWebhookMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @var \NotificationChannels\DiscordWebhook\DiscordWebhookMessage */
    protected $message;

    public function setUp()
    {
        parent::setUp();
        $this->message = new DiscordWebhookMessage();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /** @test */
    public function it_can_construct_with_a_new_message()
    {
        $message = new DiscordWebhookMessage('my construct message');
        $this->assertEquals('my construct message', $message->content);
    }

    /** @test */
    public function it_can_set_the_content()
    {
        $this->message->content('my message');
        $this->assertEquals('my message', $this->message->content);
    }

    /** @test */
    public function it_can_set_the_username()
    {
        $this->message->from('me');
        $this->assertEquals('me', $this->message->username);
    }

    /** @test */
    public function it_can_set_the_username_and_avatar()
    {
        $this->message->from('with avatar', 'avatar_url');
        $this->assertEquals('with avatar', $this->message->username);
        $this->assertEquals('avatar_url', $this->message->avatar_url);
    }

    /** @test */
    public function it_can_enable_tts()
    {
        $this->message->tts();
        $this->assertEquals('true', $this->message->tts);
    }

    /** @test */
    public function it_can_disable_tts()
    {
        $this->message->tts();
        $this->message->tts(false);
        $this->assertEquals('false', $this->message->tts);
    }

    /** @test */
    public function it_can_set_file()
    {
        $this->message->file('file content', 'file name');
        $this->assertEquals('file content', Arr::get($this->message->toArray(), 'file.contents'));
        $this->assertEquals('file name', Arr::get($this->message->toArray(), 'file.filename'));
    }

    /** @test */
    public function it_can_embed_content()
    {
        $this->message->embed(function ($embed) {
            $embed->description('embed description');
        });
        $this->assertEquals('embed description', Arr::get(Arr::first($this->message->embeds)->toArray(), 'description'));
    }
}
