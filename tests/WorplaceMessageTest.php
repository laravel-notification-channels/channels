<?php

namespace NotificationChannels\Workplace\Test;

use PHPUnit\Framework\TestCase;
use NotificationChannels\Workplace\WorkplaceMessage;

class WorplaceMessageTest extends TestCase
{
    /** @test */
    public function it_accepts_content_when_constructed()
    {
        $message = new WorkplaceMessage('Laravel Notification Channels are awesome!');
        $this->assertEquals('Laravel Notification Channels are awesome!', $message->getContent());
    }

    /** @test */
    public function the_default_parse_mode_is_markdown()
    {
        $message = new WorkplaceMessage();
        $this->assertTrue($message->isMarkdown());
    }

    /** @test */
    public function the_parse_mode_can_be_set()
    {
        $message = new WorkplaceMessage();
        $message->asPlainText();
        $this->assertTrue($message->isPlainText());
        $this->assertFalse($message->isMarkdown());
    }

    /** @test */
    public function the_notification_content_can_be_set()
    {
        $message = new WorkplaceMessage();
        $message->content('Laravel Notification Channels are awesome!');
        $this->assertEquals('Laravel Notification Channels are awesome!', $message->getContent());
    }
}
