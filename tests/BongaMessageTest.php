<?php

namespace NotificationChannels\Bonga\Test;

use NotificationChannels\Bonga\BongaMessage;

class BongaMessageTest extends TestCase
{
    /** @var BongaMessage */
    protected $message;

    public function setUp(): void
    {
        parent::setUp();
        $this->message = new BongaMessage();
    }

    /** @test */
    public function it_can_get_the_content()
    {
        $this->message->content('myMessage');
        $this->assertEquals('myMessage', $this->message->getContent());
    }
}