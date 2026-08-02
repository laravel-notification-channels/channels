<?php

namespace NotificationChannels\SmsIr\Test;

use Illuminate\Support\Carbon;
use NotificationChannels\SmsIr\SmsIrMessage;
use PHPUnit\Framework\TestCase;

class SmsIrMessageTest extends TestCase
{
    public function test_it_creates_a_plain_text_message(): void
    {
        $message = SmsIrMessage::create('hello');

        $this->assertSame('hello', $message->content);
        $this->assertFalse($message->isVerification());
    }

    public function test_it_creates_a_verification_message(): void
    {
        $message = SmsIrMessage::verify(100);

        $this->assertSame(100, $message->template);
        $this->assertTrue($message->isVerification());
    }

    public function test_it_accepts_a_named_template(): void
    {
        // Resolved against the `smsir.templates` config by the underlying client.
        $this->assertSame('otp', SmsIrMessage::verify('otp')->template);
    }

    public function test_it_merges_template_parameters(): void
    {
        $message = SmsIrMessage::verify('otp')
            ->with(['CODE' => '1234'])
            ->with(['NAME' => 'Ali']);

        $this->assertSame(['CODE' => '1234', 'NAME' => 'Ali'], $message->parameters);
    }

    public function test_it_sets_the_line_and_schedule(): void
    {
        $moment = Carbon::createFromTimestamp(1754092800);

        $message = SmsIrMessage::create('hi')->from('30007')->sendAt($moment);

        $this->assertSame('30007', $message->from);
        $this->assertSame($moment, $message->sendAt);
    }

    public function test_setters_are_chainable(): void
    {
        $message = SmsIrMessage::create()->content('hi')->from('30007');

        $this->assertInstanceOf(SmsIrMessage::class, $message);
        $this->assertSame('hi', $message->content);
    }
}
