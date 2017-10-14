<?php

namespace NotificationChannels\SmscRu\Test;

use NotificationChannels\RedsmsRu\RedsmsRuMessage;

class RedsmsRuMessageTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_can_accept_a_content_when_constructing_a_message()
    {
        $message = new RedsmsRuMessage('hello');

        $this->assertEquals('hello', $message->text);
    }

    /** @test */
    public function it_can_set_the_content()
    {
        $message = (new RedsmsRuMessage)->text('hello');

        $this->assertEquals('hello', $message->text);
    }
}
