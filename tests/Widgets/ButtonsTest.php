<?php

namespace NotificationChannels\GoogleChat\Tests\Widgets;

use NotificationChannels\GoogleChat\Components\Button\ImageButton;
use NotificationChannels\GoogleChat\Components\Button\TextButton;
use NotificationChannels\GoogleChat\Enums\Icon;
use NotificationChannels\GoogleChat\Tests\TestCase;
use NotificationChannels\GoogleChat\Widgets\Buttons;

class ButtonsTest extends TestCase
{
    public function test_it_can_create_with_buttons()
    {
        $widget = Buttons::create([
            $textButton = TextButton::create('https://example.org', 'Example'),
            $imageButton = ImageButton::create('https://example.com', Icon::TRAIN),
        ]);

        $this->assertEquals(
            [
                'buttons' => [
                    $textButton,
                    $imageButton,
                ],
            ],
            $widget->toArray()
        );
    }
}
