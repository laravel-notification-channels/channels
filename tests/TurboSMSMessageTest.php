<?php

namespace NotificationChannels\TurboSMS;

use PHPUnit\Framework\TestCase;

class TurboSMSMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiate()
    {
        $instance = new TurboSMSMessage('TEST_BODY');
        $this->assertInstanceOf(TurboSMSMessage::class, $instance);
    }
}
