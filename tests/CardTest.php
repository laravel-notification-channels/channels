<?php

namespace NotificationChannels\GoogleChat\Tests;

use NotificationChannels\GoogleChat\Card;
use NotificationChannels\GoogleChat\Enums\ImageStyle;
use NotificationChannels\GoogleChat\Exceptions\CouldNotSendNotification;
use NotificationChannels\GoogleChat\Section;
use stdClass;

class CardTest extends TestCase
{
    public function test_it_creates_header_element()
    {
        $card = Card::create()
            ->header(
                'Header Title',
                'Header Subtitle',
                'Header Image URL',
                ImageStyle::SQUARE
            );

        $this->assertEquals(
            [
                'header' => [
                    'title' => 'Header Title',
                    'subtitle' => 'Header Subtitle',
                    'imageUrl' => 'Header Image URL',
                    'imageStyle' => 'IMAGE',
                ],
                'sections' => [],
            ],
            $card->toArray()
        );
    }

    public function test_it_rejects_non_sections()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Cannot pass object of type: stdClass');

        Card::create(new stdClass);
    }

    public function test_it_can_add_sections()
    {
        $sectionA = Section::create()->header('Section A');
        $sectionB = Section::create()->header('Section B');

        $card = Card::create([$sectionA, $sectionB]);

        $this->assertEquals(
            [
                'sections' => [
                    $sectionA,
                    $sectionB,
                ],
            ],
            $card->toArray()
        );
    }
}
