<?php

namespace NotificationChannels\WhatsApp\Test;

use NotificationChannels\WhatsApp\Component\Currency;
use NotificationChannels\WhatsApp\Component\Document;
use NotificationChannels\WhatsApp\Component\Image;
use NotificationChannels\WhatsApp\Component\Text;
use NotificationChannels\WhatsApp\Component\Video;
use NotificationChannels\WhatsApp\WhatsAppTemplate;
use PHPUnit\Framework\TestCase;

final class WhatsAppTemplateTest extends TestCase
{
    /** @test */
    public function the_notification_recipient_can_be_set()
    {
        $message = WhatsAppTemplate::create()
            ->to('346762014584');

        $this->assertEquals('346762014584', $message->recipient());
    }

    /** @test */
    public function the_notification_name_can_be_set()
    {
        $message = WhatsAppTemplate::create()
            ->name('invoice_created');

        $this->assertEquals('invoice_created', $message->configuredName());
    }

    /** @test */
    public function the_notification_language_can_be_set()
    {
        $message = WhatsAppTemplate::create()
            ->language('es_fake');

        $this->assertEquals('es_fake', $message->configuredLanguage());
    }

    /** @test */
    public function the_notification_component_header_can_be_set()
    {
        $message = WhatsAppTemplate::create()
            ->header(new Currency(10, 'USD'))
            ->header(new Document('https://netflie.es/document.pdf'))
            ->header(new Video('https://netflie.es/video.webm'));

        $expectedHeader = [
            [
                'type' => 'currency',
                'currency' => ['amount_1000' => 10000, 'code' => 'USD'],
            ],
            [
                'type' => 'document',
                'document' => ['link' => 'https://netflie.es/document.pdf'],
            ],
            [
                'type' => 'video',
                'video' => ['link' => 'https://netflie.es/video.webm'],
            ],
        ];

        $this->assertEquals($expectedHeader, $message->components()->header());
    }

    /** @test */
    public function the_notification_component_body_can_be_set()
    {
        $message = WhatsAppTemplate::create()
            ->body(new Text('Mr Jones'))
            ->body(new Image('https://netflie.es/image.png'));

        $expectedHeader = [
            [
                'type' => 'text',
                'text' => 'Mr Jones',
            ],
            [
                'type' => 'image',
                'image' => ['link' => 'https://netflie.es/image.png'],
            ],
        ];

        $this->assertEquals($expectedHeader, $message->components()->body());
    }
}
