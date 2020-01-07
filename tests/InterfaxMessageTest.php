<?php

namespace NotificationChannels\Interfax\Test;

use NotificationChannels\Interfax\InterfaxMessage;

class InterfaxMessageTest extends TestCase
{
    /** @test */
    public function it_should_check_the_status_via_refresh()
    {
        $message = (new InterfaxMessage)
                        ->checkStatus()
                        ->user(new TestNotifiable)
                        ->file('test-file.pdf');

        $this->assertTrue($message->shouldCheckStatus());
    }

    /** @test */
    public function it_should_not_check_the_status_via_refresh_manual()
    {
        $message = (new InterfaxMessage)
                        ->checkStatus(false)
                        ->user(new TestNotifiable)
                        ->file('test-file.pdf');

        $this->assertFalse($message->shouldCheckStatus());
    }

    /** @test */
    public function it_should_not_check_the_status_via_refresh_default()
    {
        $message = (new InterfaxMessage)
                        ->user(new TestNotifiable)
                        ->file('test-file.pdf');

        $this->assertFalse($message->shouldCheckStatus());
    }
}
