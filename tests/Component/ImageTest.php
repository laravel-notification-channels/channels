<?php

namespace NotificationChannels\WhatsApp\Test;

use NotificationChannels\WhatsApp\Component\Image;
use PHPUnit\Framework\TestCase;

final class ImageTest extends TestCase
{
    /** @test */
    public function currency_is_valid()
    {
        $currency = new Image('https://netflie.es/image.png');
        $expectedValue = [
            'type' => 'image',
            'image' => [
                'link' => 'https://netflie.es/image.png',
            ],
        ];

        $this->assertEquals($expectedValue, $currency->toArray());
    }
}
