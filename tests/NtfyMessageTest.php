<?php


use NotificationChannels\Ntfy\NtfyMessage;
use PHPUnit\Framework\TestCase;

class NtfyMessageTest extends TestCase
{

    public function testBody()
    {
        $message = new NtfyMessage();
        $message->body('body');
        $this->assertEquals('body', $message->content);
    }

    public function testActions()
    {
        $message = new NtfyMessage();
        $message->actions(['action1', 'action2']);
        $this->assertEquals(['action1', 'action2'], $message->actions);
    }

    public function testContent()
    {
        $message = new NtfyMessage();
        $message->content('content');
        $this->assertEquals('content', $message->content);
    }

    public function testTopic()
    {
        $message = new NtfyMessage();
        $message->topic('topic');
        $this->assertEquals('topic', $message->topic);
    }

    public function testPriority()
    {
        $message = new NtfyMessage();
        $message->priority(1);
        $this->assertEquals(1, $message->priority);
    }

    public function testTitle()
    {
        $message = new NtfyMessage();
        $message->title('title');
        $this->assertEquals('title', $message->title);
    }
}
