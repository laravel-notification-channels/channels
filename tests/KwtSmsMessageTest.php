<?php

namespace NotificationChannels\KwtSms\Tests;

use NotificationChannels\KwtSms\KwtSmsMessage;
use PHPUnit\Framework\TestCase;

class KwtSmsMessageTest extends TestCase
{
    public function test_can_create_message_with_content(): void
    {
        $message = new KwtSmsMessage();
        $message->content('Hello');

        $this->assertSame('Hello', $message->getContent());
    }

    public function test_can_create_message_via_static_create(): void
    {
        $message = KwtSmsMessage::create('Hello');

        $this->assertSame('Hello', $message->getContent());
    }

    public function test_can_set_sender(): void
    {
        $message = KwtSmsMessage::create('Hello')->sender('MySender');

        $this->assertSame('MySender', $message->getSender());
    }

    public function test_content_defaults_to_empty_string(): void
    {
        $message = new KwtSmsMessage();

        $this->assertSame('', $message->getContent());
    }

    public function test_sender_defaults_to_null(): void
    {
        $message = new KwtSmsMessage();

        $this->assertNull($message->getSender());
    }
}
