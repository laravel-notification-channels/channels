<?php

namespace NotificationChannels\WhatsApp\Test;

use NotificationChannels\WhatsApp\Component\Video;
use PHPUnit\Framework\TestCase;

final class VideoTest extends TestCase
{
    /** @test */
    public function currency_is_valid()
    {
        $currency = new Video('https://netflie.es/video.webm');
        $expectedValue = [
            'type' => 'video',
            'video' => [
                'link' => 'https://netflie.es/video.webm',
            ],
        ];

        $this->assertEquals($expectedValue, $currency->toArray());
    }
}
