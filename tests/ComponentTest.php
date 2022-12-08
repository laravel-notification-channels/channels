<?php

namespace NotificationChannels\WhatsApp\Test;

use NotificationChannels\WhatsApp\Component;
use PHPUnit\Framework\TestCase;

final class ComponentTest extends TestCase
{
    /** @test */
    public function it_can_return_a_currency_component()
    {
        $component = Component::currency(10, 'EUR');

        $this->assertInstanceOf(Component\Currency::class, $component);
    }

    /** @test */
    public function it_can_return_a_datetime_component()
    {
        $component = Component::dateTime(new \DateTimeImmutable());

        $this->assertInstanceOf(Component\DateTime::class, $component);
    }

    /** @test */
    public function it_can_return_a_document_component()
    {
        $component = Component::document('https://www.netflie.es/my_document.pdf');

        $this->assertInstanceOf(Component\Document::class, $component);
    }

    /** @test */
    public function it_can_return_an_image_component()
    {
        $component = Component::image('https://www.netflie.es/my_image.png');

        $this->assertInstanceOf(Component\Image::class, $component);
    }

    /** @test */
    public function it_can_return_a_text_component()
    {
        $component = Component::text('Hey there!');

        $this->assertInstanceOf(Component\Text::class, $component);
    }

    /** @test */
    public function it_can_return_a_vide_component()
    {
        $component = Component::video('https://www.netflie.es/my_image.webm');

        $this->assertInstanceOf(Component\Video::class, $component);
    }
}
