<?php

namespace NotificationChannels\KChat\Test;

use NotificationChannels\KChat\KChatMessage;
use PHPUnit\Framework\TestCase;

/**
 * Class KChatMessageTest.
 */
class KChatMessageTest extends TestCase
{
    /** @test */
    public function it_accepts_content_when_constructed(): void
    {
        $message = new KChatMessage('Laravel Notification Channels are awesome!');
        $this->assertEquals('Laravel Notification Channels are awesome!', $message->getPayloadValue('message'));
    }

    /** @test */
    public function a_content_can_be_set(): void
    {
        $message = new KChatMessage();
        $message->content('Laravel Notification Test content');
        $this->assertEquals('Laravel Notification Test content', $message->getPayloadValue('message'));
    }

    /** @test */
    public function a_channel_id_can_be_set(): void
    {
        $message = new KChatMessage();
        $message->to('123456789');
        $this->assertEquals('123456789', $message->getPayloadValue('channel_id'));
    }

    /** @test */
    public function a_comment_id_can_be_set(): void
    {
        $message = new KChatMessage();
        $message->commentTo('123456789');
        $this->assertEquals('123456789', $message->getPayloadValue('root_id'));
    }

    /** @test */
    public function a_comment_id_can_be_empty(): void
    {
        $message = new KChatMessage();
        $message->commentTo('');
        $this->assertEquals(null, $message->getPayloadValue('root_id'));
    }

    /** @test */
    public function is_to_empty(): void
    {
        $message = new KChatMessage();
        $this->assertEquals(true, $message->toNotGiven());
    }

    /** @test */
    public function is_to_not_empty(): void
    {
        $message = new KChatMessage();
        $message->to('123456789');
        $this->assertEquals(false, $message->toNotGiven());
    }
}
