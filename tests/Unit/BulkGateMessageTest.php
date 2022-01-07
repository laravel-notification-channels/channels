<?php

declare(strict_types=1);

namespace NotificationChannels\BulkGate\Test\Unit;

use NotificationChannels\BulkGate\BulkGateMessage;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class BulkGateMessageTest extends TestCase
{
    public function testTo()
    {
        $message = new BulkGateMessage();

        $message->to('+420123456789');

        $this->assertEquals('420123456789', $message->getMessage()->getPhoneNumber()->getPhoneNumber());
    }

    public function testToWithCountryCode()
    {
        $message = new BulkGateMessage();

        $message->to('123456789', 'uk');

        $this->assertEquals('123456789', $message->getMessage()->getPhoneNumber()->getPhoneNumber());
        $this->assertEquals('uk', $message->getMessage()->getPhoneNumber()->getIso());
    }

    public function testTextOnly()
    {
        $message = new BulkGateMessage();

        $message->to('+420123456789')
            ->text('Hello World');

        $this->assertEquals('Hello World', $message->getMessage()->getText());
    }

    public function testHasPhoneNumber()
    {
        $message = new BulkGateMessage();

        $message->to('+420123456789');

        $this->assertTrue($message->hasPhoneNumber());
    }

    public function testStaticCreateTextOnly()
    {
        $message = BulkGateMessage::create('Hello World');

        $message->to('+420123456789');

        $this->assertEquals('Hello World', $message->getMessage()->getText());
    }

    public function testStaticCreateWithAllParameters()
    {
        $message = BulkGateMessage::create('HelloWorld', '+420123456789', 'uk');

        $this->assertEquals('HelloWorld', $message->getMessage()->getText());
        $this->assertEquals('420123456789', $message->getMessage()->getPhoneNumber()->getPhoneNumber());
        $this->assertEquals('uk', $message->getMessage()->getPhoneNumber()->getIso());
    }
}
