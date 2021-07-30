<?php

namespace NotificationChannels\SparrowSMS;

use PHPUnit\Framework\TestCase;

class SparrowSMSMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiate()
    {
        $instance = new SparrowSMSMessage('TEST_BODY');

        $this->assertInstanceOf(SparrowSMSMessage::class, $instance);
    }
}