<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests;

use FtwSoft\NotificationChannels\Intercom\IntercomMessage;
use PHPUnit\Framework\TestCase;

class IntercomMessageTest extends TestCase
{
    public function testThatTypeInappConstantSetCorrectly(): void
    {
        $this->assertEquals('inapp', IntercomMessage::TYPE_INAPP);
    }

    public function testThatTypeEmailConstantSetCorrectly(): void
    {
        $this->assertEquals('email', IntercomMessage::TYPE_EMAIL);
    }

    public function testThatTemplatePlainConstantSetCorrectly(): void
    {
        $this->assertEquals('plain', IntercomMessage::TEMPLATE_PLAIN);
    }

    public function testThatTemplatePersonalConstantSetCorrectly(): void
    {
        $this->assertEquals('personal', IntercomMessage::TEMPLATE_PERSONAL);
    }

    public function testItAcceptsBodyWhenConstructed(): void
    {
        $message = new IntercomMessage('Intercom message test');
        $this->assertEquals('Intercom message test', $message->payload['body']);
    }

    public function testThatDefaultMessageTypeIsInapp(): void
    {
        $message = new IntercomMessage();
        $this->assertEquals(IntercomMessage::TYPE_INAPP, $message->payload['message_type']);
    }

    public function testThatBodyCanBeSet(): void
    {
        $message = new IntercomMessage('Intercom message test');
        $message->body('Some other intercom body');
        $this->assertEquals('Some other intercom body', $message->payload['body']);
    }

    public function testThatMessageTypeToEmailCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->email();
        $this->assertEquals(IntercomMessage::TYPE_EMAIL, $message->payload['message_type']);
    }

    public function testThatMessageTypeToInappCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->email()->inapp();
        $this->assertEquals(IntercomMessage::TYPE_INAPP, $message->payload['message_type']);
    }

    public function testThatSubjectCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->subject('Some interesting subject');
        $this->assertEquals('Some interesting subject', $message->payload['subject']);
    }

    public function testThatTemplatePlainCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->plain();
        $this->assertEquals(IntercomMessage::TEMPLATE_PLAIN, $message->payload['template']);
    }

    public function testThatTemplatePersonalCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->personal();
        $this->assertEquals(IntercomMessage::TEMPLATE_PERSONAL, $message->payload['template']);
    }

    public function testThatSenderCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->from(123);
        $this->assertEquals(
            [
                'type' => 'admin',
                'id'   => 123,
            ],
            $message->payload['from']
        );
    }

    public function testThatRecipientCanBeSet(): void
    {
        $message = new IntercomMessage();
        $expected = [
            'type'  => 'user',
            'email' => 'foo@bar.baz',
        ];
        $message->to($expected);

        $this->assertEquals($expected, $message->payload['to']);
    }

    public function testThatRecipientUserIdCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->toUserId(456);
        $this->assertEquals(
            [
                'type' => 'user',
                'id'   => 456,
            ],
            $message->payload['to']
        );
    }

    public function testThatRecipientUserEmailCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->toUserEmail('foo@bar.com');
        $this->assertEquals(
            [
                'type'  => 'user',
                'email' => 'foo@bar.com',
            ],
            $message->payload['to']
        );
    }

    public function testThatContactIdCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->toContactId(789);
        $this->assertEquals(
            [
                'type' => 'contact',
                'id'   => 789,
            ],
            $message->payload['to']
        );
    }

    public function testItCanDetermiteIfToIsNotGiven()
    {
        $message = new IntercomMessage();
        $this->assertFalse($message->toIsGiven());

        $message->toUserId(123);
        $this->assertTrue($message->toIsGiven());
    }

    public function testInCanDetermineWhenRequiredParamsAreNotSet(): void
    {
        $message = new IntercomMessage();
        $this->assertFalse($message->isValid());

        $message->body('Some body');
        $this->assertFalse($message->isValid());

        $message->from(123);
        $this->assertFalse($message->isValid());

        $message->toUserId(321);
        $this->assertTrue($message->isValid());
    }

    public function testItCanReturnPayloadAsAnArray(): void
    {
        $message = new IntercomMessage();

        $message
            ->email()
            ->personal()
            ->from(123)
            ->toUserEmail('recipient@foo.bar')
            ->subject('Test case')
            ->body('Some message');

        $expected = [
            'message_type' => 'email',
            'template'     => 'personal',
            'from'         => [
                'type' => 'admin',
                'id'   => '123',
            ],
            'to'           => [
                'type'  => 'user',
                'email' => 'recipient@foo.bar',
            ],
            'subject'      => 'Test case',
            'body'         => 'Some message',
        ];

        $this->assertEquals($expected, $message->toArray());
    }

    public function testThatStaticCreateMethodProvidesBodyToObject(): void
    {
        $message = IntercomMessage::create();
        $this->assertFalse(isset($message->payload['body']));

        $message = IntercomMessage::create('Intercom message test');
        $this->assertEquals('Intercom message test', $message->payload['body']);
    }
}
