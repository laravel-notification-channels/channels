<?php

namespace NotificationChannels\Asterisk\Test;

use NotificationChannels\Asterisk\AsteriskMessage;

class AsteriskMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_construct_with_a_new_message()
    {
        $actual = AsteriskMessage::create('Foo');
        $this->assertEquals('Foo', $actual->toArray()['message']);
    }

    /** @test */
    public function it_can_set_new_content()
    {
        $actual = AsteriskMessage::create();
        $this->assertNull($actual->toArray()['message']);
        $actual->content('Bar');
        $this->assertEquals('Bar', $actual->toArray()['message']);
    }
}
