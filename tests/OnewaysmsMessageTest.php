<?php

namespace NotificationChannels\Onewaysms\Tests;

use NotificationChannels\Onewaysms\OnewaysmsMessage;

class OnewaysmsMessageTest extends TestCase
{
    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message(): void
    {
        $message = new OnewaysmsMessage('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_content(): void
    {
        $message = (new OnewaysmsMessage())->content('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_from(): void
    {
        $message = (new OnewaysmsMessage())->sender('Onewaysms');

        $this->assertEquals('Onewaysms', $message->sender);
    }
}
