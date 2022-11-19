<?php

namespace NotificationChannels\WhatsApp\Test;

use NotificationChannels\WhatsApp\Component\Document;
use NotificationChannels\WhatsApp\Exceptions\UnsupportedMediaValue;
use PHPUnit\Framework\TestCase;

final class DocumentTest extends TestCase
{
    /** @test */
    public function the_document_link_is_a_supported_document()
    {
        $document = new Document('https://www.netflie.es/document.pdf');
        $expectedValue = [
            'type' => 'document',
            'document' => [
                'link' => 'https://www.netflie.es/document.pdf',
            ],
        ];

        $this->assertEquals($expectedValue, $document->toArray());
    }

    /** @test */
    public function the_document_link_is_a_unsupported_document()
    {
        $this->expectException(UnsupportedMediaValue::class);
        new Document('https://www.netflie.es/document.doc');
    }
}
