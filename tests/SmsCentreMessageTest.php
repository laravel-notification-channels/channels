<?php

namespace NotificationChannels\SmsCentre\Test;

use PHPUnit_Framework_TestCase;
use NotificationChannels\SmsCentre\SmsCentreMessage;

class SmsCentreMessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SmsCentreMessage
     */
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->message = new SmsCentreMessage();
    }

    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message()
    {
        $message = new SmsCentreMessage('hello');

        $this->assertEquals('hello', $message->content);
    }

    /** @test */
    public function it_can_set_the_content()
    {
        $this->message->content('hello');

        $this->assertEquals('hello', $this->message->content);
    }
    /** @test */
    public function it_can_set_the_from()
    {
        $this->message->from('John_Doe');

        $this->assertEquals('John_Doe', $this->message->from);
    }

    /** @test */
    public function it_can_convert_self_to_array()
    {
        $params = $this->message->toArray();

        $this->assertArraySubset($params, [
            'charset' => 'utf-8',
            'sender'  => $this->message->from,
            'mes'     => $this->message->content,
        ]);
    }
}
