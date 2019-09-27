<?php

namespace FtwSoft\NotificationChannels\Intercom\Tests;

use FtwSoft\NotificationChannels\Intercom\IntercomMessage;
use PHPUnit\Framework\TestCase;

class IntercomMessageTest extends TestCase
{
    public function testThatTypeInappConstantSetCorrectly(): void
    {
        self::assertEquals('inapp', IntercomMessage::TYPE_INAPP);
    }

    public function testThatTypeEmailConstantSetCorrectly(): void
    {
        self::assertEquals('email', IntercomMessage::TYPE_EMAIL);
    }

    public function testThatTemplatePlainConstantSetCorrectly(): void
    {
        self::assertEquals('plain', IntercomMessage::TEMPLATE_PLAIN);
    }

    public function testThatTemplatePersonalConstantSetCorrectly(): void
    {
        self::assertEquals('personal', IntercomMessage::TEMPLATE_PERSONAL);
    }

    public function testItAcceptsBodyWhenConstructed(): void
    {
        $message = new IntercomMessage('Intercom message test');
        self::assertEquals('Intercom message test', $message->payload['body']);
    }

    public function testThatDefaultMessageTypeIsInapp(): void
    {
        $message = new IntercomMessage();
        self::assertEquals(IntercomMessage::TYPE_INAPP, $message->payload['message_type']);
    }

    public function testThatBodyCanBeSet(): void
    {
        $message = new IntercomMessage('Intercom message test');
        $message->body('Some other intercom body');
        self::assertEquals('Some other intercom body', $message->payload['body']);
    }

    public function testThatMessageTypeToEmailCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->email();
        self::assertEquals(IntercomMessage::TYPE_EMAIL, $message->payload['message_type']);
    }

    public function testThatMessageTypeToInappCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->email()->inapp();
        self::assertEquals(IntercomMessage::TYPE_INAPP, $message->payload['message_type']);
    }

    public function testThatSubjectCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->subject('Some interesting subject');
        self::assertEquals('Some interesting subject', $message->payload['subject']);
    }

    public function testThatTemplatePlainCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->plain();
        self::assertEquals(IntercomMessage::TEMPLATE_PLAIN, $message->payload['template']);
    }

    public function testThatTemplatePersonalCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->personal();
        self::assertEquals(IntercomMessage::TEMPLATE_PERSONAL, $message->payload['template']);
    }

    public function testThatSenderCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->from(123);
        self::assertEquals(
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

        self::assertEquals($expected, $message->payload['to']);
    }

    public function testThatRecipientUserIdCanBeSet(): void
    {
        $message = new IntercomMessage();
        $message->toUserId(456);
        self::assertEquals(
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
        self::assertEquals(
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
        self::assertEquals(
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
        self::assertFalse($message->toIsGiven());

        $message->toUserId(123);
        self::assertTrue($message->toIsGiven());
    }

    public function testInCanDetermineWhenRequiredParamsAreNotSet(): void
    {
        $message = new IntercomMessage();
        self::assertFalse($message->isValid());

        $message->body('Some body');
        self::assertFalse($message->isValid());

        $message->from(123);
        self::assertFalse($message->isValid());

        $message->toUserId(321);
        self::assertTrue($message->isValid());
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

        self::assertEquals($expected, $message->toArray());
    }

    public function testThatStaticCreateMethodProvidesBodyToObject(): void
    {
        $message = IntercomMessage::create();
        self::assertFalse(isset($message->payload['body']));

        $message = IntercomMessage::create('Intercom message test');
        self::assertEquals('Intercom message test', $message->payload['body']);
    }
}
