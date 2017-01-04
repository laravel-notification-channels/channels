<?php

namespace NotificationChannels\Chatwork\Test;

use NotificationChannels\Chatwork\ChatworkMessage;

class ChatworkMessageTest extends \PHPUnit_Framework_TestCase
{

    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message()
    {
        $message = new ChatworkMessage('hello');
        $this->assertEquals('hello', $message->message);
    }

    /** @test */
    public function it_can_accept_a_content_when_creating_a_message()
    {
        $message = ChatworkMessage::create('hello');
        $this->assertEquals('hello', $message->message);
    }

    /** @test */
    public function it_can_set_the_message()
    {
        $message = (new ChatworkMessage())->message('hello');
        $this->assertEquals('hello', $message->message);
    }

    /** @test */
    public function it_can_set_the_roomid()
    {
        $message = (new ChatworkMessage())->roomId('room');
        $this->assertEquals('room', $message->roomId);
    }

    /** @test */
    public function it_can_set_the_to()
    {
        $message = (new ChatworkMessage())->to('99999');
        $this->assertEquals('99999', $message->to);
    }
}
