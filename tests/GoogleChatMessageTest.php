<?php

namespace NotificationChannels\GoogleChat\Tests;

use NotificationChannels\GoogleChat\Card;
use NotificationChannels\GoogleChat\Exceptions\CouldNotSendNotification;
use NotificationChannels\GoogleChat\GoogleChatMessage;

class GoogleChatMessageTest extends TestCase
{
    public function test_it_stores_space_endpoint()
    {
        $message = GoogleChatMessage::create()->to('example_space');

        $this->assertEquals('example_space', $message->getSpace());
    }

    public function test_it_creates_simple_test_message()
    {
        $message = GoogleChatMessage::create('Example Simple Message');

        $this->assertEquals(['text' => 'Example Simple Message'], $message->toArray());
    }

    public function test_it_appends_text()
    {
        $message = GoogleChatMessage::create('Example Text: ')
            ->text('More Text ')
            ->text('Even More Text');

        $this->assertEquals(
            [
                'text' => 'Example Text: More Text Even More Text',
            ],
            $message->toArray()
        );
    }

    public function test_it_creates_lines()
    {
        $message = GoogleChatMessage::create('Line 1')->line('Line 2');

        $this->assertEquals(
            [
                'text' => "Line 1\nLine 2",
            ],
            $message->toArray()
        );
    }

    public function test_it_creates_bold_text()
    {
        $message = GoogleChatMessage::create()->bold('Some Bold Text');

        $this->assertEquals(
            [
                'text' => '*Some Bold Text*',
            ],
            $message->toArray()
        );
    }

    public function test_it_creates_italic_text()
    {
        $message = GoogleChatMessage::create()->italic('Some Italic Text');

        $this->assertEquals(
            [
                'text' => '_Some Italic Text_',
            ],
            $message->toArray()
        );
    }

    public function test_it_creates_strikethrough_text()
    {
        $message = GoogleChatMessage::create()->strikethrough('Some Strikethrough Text');

        $this->assertEquals(
            [
                'text' => '~Some Strikethrough Text~',
            ],
            $message->toArray()
        );

        // Using Alias method
        $message = GoogleChatMessage::create()->strike('Some Strikethrough Text');

        $this->assertEquals(
            [
                'text' => '~Some Strikethrough Text~',
            ],
            $message->toArray()
        );
    }

    public function test_it_creates_monospace_text()
    {
        $message = GoogleChatMessage::create()->monospace('Some Monospace Text');

        $this->assertEquals(
            [
                'text' => '`Some Monospace Text`',
            ],
            $message->toArray()
        );

        // Using Alias method
        $message = GoogleChatMessage::create()->mono('Some Monospace Text');

        $this->assertEquals(
            [
                'text' => '`Some Monospace Text`',
            ],
            $message->toArray()
        );
    }

    public function test_it_creates_monospace_block_text()
    {
        $message = GoogleChatMessage::create()->monospaceBlock('Some Monospace Block Text');

        $this->assertEquals(
            [
                'text' => '```Some Monospace Block Text```',
            ],
            $message->toArray()
        );
    }

    public function test_it_creates_link_text()
    {
        $message = GoogleChatMessage::create()->link('http://example.com');

        $this->assertEquals(
            [
                'text' => 'http://example.com',
            ],
            $message->toArray()
        );

        $message = GoogleChatMessage::create()->link('http://example.com', 'Example Link');

        $this->assertEquals(
            [
                'text' => '<http://example.com|Example Link>',
            ],
            $message->toArray()
        );
    }

    public function test_it_creates_mention_text()
    {
        $message = GoogleChatMessage::create()->mention('123456789');

        $this->assertEquals(
            [
                'text' => '<users/123456789>',
            ],
            $message->toArray()
        );
    }

    public function test_it_creates_mention_all_text()
    {
        $message = GoogleChatMessage::create()->mentionAll();

        $this->assertEquals(
            [
                'text' => '<users/all>',
            ],
            $message->toArray()
        );

        $message = GoogleChatMessage::create()->mentionAll('Hey ', '!');

        $this->assertEquals(
            [
                'text' => 'Hey <users/all>!',
            ],
            $message->toArray()
        );
    }

    public function test_it_rejects_non_cards()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Cannot pass object of type: stdClass');

        GoogleChatMessage::create()->card(new \stdClass);
    }

    public function test_it_can_add_card()
    {
        $message = GoogleChatMessage::create()->card(Card::create());

        $this->assertEquals(
            [
                'cards' => [
                    [
                        'sections' => [],
                    ],
                ],
            ],
            $message->toArray()
        );
    }
}
