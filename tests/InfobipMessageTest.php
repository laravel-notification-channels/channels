<?php

namespace NotificationChannels\Infobip\Tests;

use NotificationChannels\Infobip\InfobipMessage;
use NotificationChannels\Infobip\Test\TestCase;

class InfobipMessageTest extends TestCase
{
    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message(): void
    {
        $message = new InfobipMessage('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_content(): void
    {
        $message = (new InfobipMessage())->content('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_from(): void
    {
        $message = (new InfobipMessage())->from('Infobip');

        $this->assertEquals('Infobip', $message->from);
    }
}
