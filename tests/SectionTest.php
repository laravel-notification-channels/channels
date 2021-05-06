<?php

namespace NotificationChannels\GoogleChat\Tests;

use NotificationChannels\GoogleChat\Exceptions\CouldNotSendNotification;
use NotificationChannels\GoogleChat\Section;
use NotificationChannels\GoogleChat\Widgets\TextParagraph;
use stdClass;

class SectionTest extends TestCase
{
    public function test_it_can_set_header_text()
    {
        $section = Section::create()->header('Example Header');

        $this->assertEquals(
            [
                'header' => 'Example Header',
                'widgets' => [],
            ],
            $section->toArray()
        );
    }

    public function test_it_rejects_non_widgets()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Cannot pass object of type: stdClass');

        Section::create(new stdClass);
    }

    public function test_it_can_add_widgets()
    {
        $widget = TextParagraph::create('Text content');

        $section = Section::create($widget);

        $this->assertEquals(
            [
                'widgets' => [
                    $widget,
                ],
            ],
            $section->toArray()
        );
    }
}
