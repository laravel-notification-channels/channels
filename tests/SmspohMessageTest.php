<?php

namespace NotificationChannels\Smspoh\Tests;

use NotificationChannels\Smspoh\SmspohMessage;

class SmspohMessageTest extends TestCase
{
    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message(): void
    {
        $message = new SmspohMessage('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_content(): void
    {
        $message = (new SmspohMessage())->content('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_from(): void
    {
        $message = (new SmspohMessage())->sender('Smspoh');

        $this->assertEquals('Smspoh', $message->sender);
    }

    /** @test */
    public function it_can_set_the_test(): void
    {
        $message = (new SmspohMessage())->test(1);

        $this->assertEquals(1, $message->test);
    }
}
