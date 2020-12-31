<?php

namespace NotificationChannels\Unifonic\Test;

use NotificationChannels\Unifonic\UnifonicMessage;
use PHPUnit\Framework\TestCase;

class UnifonicMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $message = UnifonicMessage::create();

        $this->assertInstanceOf(UnifonicMessage::class, $message);
    }
    
    /** @test */
    public function it_supports_create_method()
    {
        $message = UnifonicMessage::create('Foo');

        $this->assertInstanceOf(UnifonicMessage::class, $message);
        $this->assertEquals('Foo', $message->getContent());
    }

}