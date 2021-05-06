<?php

namespace NotificationChannels\GoogleChat\Tests\Widgets;

use NotificationChannels\GoogleChat\Tests\TestCase;
use NotificationChannels\GoogleChat\Widgets\TextParagraph;

class TextParagraphTest extends TestCase
{
    public function test_it_can_create_with_simple_text()
    {
        $widget = TextParagraph::create('Example Text');

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => 'Example Text',
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_appends_text()
    {
        $widget = TextParagraph::create('Text 1')
            ->text('-Text 2')
            ->text('-Text 3');

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => 'Text 1-Text 2-Text 3',
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_creates_bold_text()
    {
        $widget = TextParagraph::create()->bold('Bold Text');

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => '<b>Bold Text</b>',
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_creates_italic_text()
    {
        $widget = TextParagraph::create()->italic('Italic Text');

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => '<i>Italic Text</i>',
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_creates_underline_text()
    {
        $widget = TextParagraph::create()->underline('Underline Text');

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => '<u>Underline Text</u>',
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_creates_strikethrough_text()
    {
        $widget = TextParagraph::create()->strikethrough('Strikethrough Text');

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => '<strike>Strikethrough Text</strike>',
                ],
            ],
            $widget->toArray()
        );

        $widget = TextParagraph::create()->strike('Strikethrough Text');

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => '<strike>Strikethrough Text</strike>',
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_creates_colored_text()
    {
        $widget = TextParagraph::create()->color('Colored Text', '#0000FF');

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => '<font color="#0000FF">Colored Text</font>',
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_creates_link_text()
    {
        $widget = TextParagraph::create()->link('https://example.com');

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => '<a href="https://example.com">https://example.com</a>',
                ],
            ],
            $widget->toArray()
        );

        $widget = TextParagraph::create()->link('https://example.com', 'Example');

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => '<a href="https://example.com">Example</a>',
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_creates_break()
    {
        $widget = TextParagraph::create()->break();

        $this->assertEquals(
            [
                'textParagraph' => [
                    'text' => '<br>',
                ],
            ],
            $widget->toArray()
        );
    }
}
