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
        $message = (new GitterMessage())->room('room');

        $this->assertEquals('room', $message->room);
    }

    /** @test */
    public function it_can_set_the_from()
    {
        $message = (new GitterMessage())->from('token');

        $this->assertEquals('token', $message->from);
    }

    /** @test */
    public function it_can_convert_self_to_array()
    {
        $message = GitterMessage::create('hello')->from('token')->room('room');

        $params = $message->toArray();

        $this->assertArraySubset($params, [
            'room' => 'room',
            'from' => 'token',
            'text' => 'hello',
        ]);
    }
}
