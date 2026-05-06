<?php

namespace NotificationChannels\Cmsms\Test;

use NotificationChannels\TelnectSms\TelnectSmsMessage;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TelnectSmsMessageTest extends TestCase
{
    public static $latestResponse;

    #[Test]
    public function it_can_be_instantiated()
    {
        $message = TelnectSmsMessage::create();

        $this->assertInstanceOf(TelnectSmsMessage::class, $message);
    }

    #[Test]
    public function it_can_set_body()
    {
        $message = TelnectSmsMessage::create()->body('Bar');

        $this->assertEquals('Bar', $message->getBody());
    }

    #[Test]
    public function it_can_set_sender()
    {
        $message = TelnectSmsMessage::create()->body('Bar')
            ->sender('Company name');

        $this->assertEquals('Company name', $message->getSender());
    }
}
