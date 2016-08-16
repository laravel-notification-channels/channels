<?php

namespace NotificationChannels\Gitter\Test;

use NotificationChannels\Gitter\GitterMessage;

class GitterMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message()
    {
        $message = new GitterMessage('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_accept_a_content_when_creating_a_message()
    {
        $message = GitterMessage::create('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_content()
    {
        $message = (new GitterMessage())->content('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_room()
    {
        $message = (new GitterMessage())->room('laravelrus');

        $this->assertEquals('laravelrus', $message->room);
    }

    /** @test */
    public function it_can_set_the_from()
    {
        $message = (new GitterMessage())->from('1a3b4c5d6e7f89');

        $this->assertEquals('1a3b4c5d6e7f89', $message->from);
    }

    /** @test */
    public function it_can_convert_self_to_array()
    {
        $message = (new GitterMessage())->content('hello')->from('John_Doe');

        $params = $message->toArray();

        $this->assertArraySubset($params, ['text' => 'hello']);
    }
}
