<?php

namespace NotificationChannels\GoogleChat\Tests\Widgets;

use NotificationChannels\GoogleChat\Tests\TestCase;
use NotificationChannels\GoogleChat\Widgets\Image;

class ImageTest extends TestCase
{
    public function test_it_can_create_simple_image_widget()
    {
        $widget = Image::create('https://example.com/kitten.png', 'https://example.com/on-click');

        $this->assertEquals(
            [
                'image' => [
                    'imageUrl' => 'https://example.com/kitten.png',
                    'onClick' => [
                        'openLink' => [
                            'url' => 'https://example.com/on-click',
                        ],
                    ],
                ],
            ],
            $widget->toArray()
        );
    }
}
