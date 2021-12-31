<?php

namespace NotificationChannels\WXWork\Test;

use NotificationChannels\WXWork\WXWorkMessage;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /** @var \NotificationChannels\WXWork\WXWorkMessage */
    protected $message;

    public function setUp(): void
    {
        parent::setUp();
        $this->message = new WXWorkMessage();
    }

    /** @test */
    public function it_accepts_content_when_constructing_a_message()
    {
        $message = new WXWorkMessage('foobar');

        $this->assertEquals('foobar', $message->getContent());
    }

    /** @test */
    public function it_provides_a_create_method()
    {
        $message = WXWorkMessage::create('foobar');

        $this->assertEquals('foobar', $message->getContent());
    }

    /** @test */
    public function it_can_set_the_wxwork_content()
    {
        $this->message->content('foobar');
        $this->assertEquals('foobar', $this->message->getContent());
    }
}
