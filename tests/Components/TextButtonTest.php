<?php

namespace NotificationChannels\GoogleChat\Tests\Components;

use NotificationChannels\GoogleChat\Components\Button\TextButton;
use NotificationChannels\GoogleChat\Tests\TestCase;

class TextButtonTest extends TestCase
{
    public function test_it_can_create_button()
    {
        $button = TextButton::create('https://example.org', 'OPEN');

        $this->assertEquals(
            [
                'textButton' => [
                    'text' => 'OPEN',
                    'onClick' => [
                        'openLink' => [
                            'url' => 'https://example.org',
                        ],
                    ],
                ],
            ],
            $button->toArray()
        );
    }
}
