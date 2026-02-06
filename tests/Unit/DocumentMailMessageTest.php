<?php

namespace Zilmoney\OnlineCheckWriter\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterDocumentMail;

class DocumentMailMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_created_with_static_method(): void
    {
        $message = OnlineCheckWriterDocumentMail::create();

        $this->assertInstanceOf(OnlineCheckWriterDocumentMail::class, $message);
    }

    /** @test */
    public function it_can_set_file_path(): void
    {
        $message = OnlineCheckWriterDocumentMail::create()
            ->file('/path/to/document.pdf');

        $array = $message->toArray();

        $this->assertEquals('/path/to/document.pdf', $array['file']);
    }

    /** @test */
    public function it_can_set_attachment_url(): void
    {
        $message = OnlineCheckWriterDocumentMail::create()
            ->attachmentUrl('https://example.com/document.pdf');

        $array = $message->toArray();

        $this->assertEquals('https://example.com/document.pdf', $array['attachmentUrl']);
    }

    /** @test */
    public function it_can_set_document_title(): void
    {
        $message = OnlineCheckWriterDocumentMail::create()
            ->documentTitle('Invoice #1234');

        $array = $message->toArray();

        $this->assertEquals('Invoice #1234', $array['documentTitle']);
    }

    /** @test */
    public function it_can_set_recipient_details_individually(): void
    {
        $message = OnlineCheckWriterDocumentMail::create()
            ->name('John Doe')
            ->company('ACME Corp')
            ->address1('123 Main St')
            ->address2('Suite 100')
            ->city('New York')
            ->state('NY')
            ->zip('10001')
            ->phone('5551234567')
            ->email('john@example.com');

        $array = $message->toArray();

        $this->assertEquals('John Doe', $array['name']);
        $this->assertEquals('ACME Corp', $array['company']);
        $this->assertEquals('123 Main St', $array['address1']);
        $this->assertEquals('Suite 100', $array['address2']);
        $this->assertEquals('New York', $array['city']);
        $this->assertEquals('NY', $array['state']);
        $this->assertEquals('10001', $array['zip']);
        $this->assertEquals('5551234567', $array['phone']);
        $this->assertEquals('john@example.com', $array['email']);
    }

    /** @test */
    public function it_can_set_recipient_details_via_to_method(): void
    {
        $address = [
            'name' => 'Jane Smith',
            'company' => 'Smith LLC',
            'address1' => '456 Oak Ave',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'zip' => '90001',
        ];

        $message = OnlineCheckWriterDocumentMail::create()
            ->to($address);

        $array = $message->toArray();

        $this->assertEquals('Jane Smith', $array['name']);
        $this->assertEquals('Smith LLC', $array['company']);
        $this->assertEquals('456 Oak Ave', $array['address1']);
        $this->assertEquals('Los Angeles', $array['city']);
        $this->assertEquals('CA', $array['state']);
        $this->assertEquals('90001', $array['zip']);
    }

    /** @test */
    public function it_can_set_sender_details(): void
    {
        $sender = [
            'name' => 'Sender Name',
            'company' => 'Sender Co',
            'address1' => '789 Sender St',
            'city' => 'Chicago',
            'state' => 'IL',
            'zip' => '60601',
        ];

        $message = OnlineCheckWriterDocumentMail::create()
            ->from($sender);

        $array = $message->toArray();

        // Sender is included in fromAddress
        $this->assertEquals('Sender Name', $array['fromAddress']['name']);
        $this->assertEquals('Sender Co', $array['fromAddress']['company']);
        $this->assertEquals('789 Sender St', $array['fromAddress']['address1']);
        $this->assertEquals('Chicago', $array['fromAddress']['city']);
    }

    /** @test */
    public function it_can_set_shipping_type(): void
    {
        $message = OnlineCheckWriterDocumentMail::create()
            ->shippingType(2);

        $array = $message->toArray();

        $this->assertEquals(2, $array['shippingTypeId']);
    }

    /** @test */
    public function it_has_default_shipping_type(): void
    {
        $message = OnlineCheckWriterDocumentMail::create();

        $array = $message->toArray();

        $this->assertEquals(3, $array['shippingTypeId']);
    }

    /** @test */
    public function it_supports_fluent_interface(): void
    {
        $message = OnlineCheckWriterDocumentMail::create()
            ->file('/path/to/doc.pdf')
            ->documentTitle('Test Doc')
            ->name('Test User')
            ->city('Test City')
            ->state('TX')
            ->zip('75001')
            ->shippingType(1);

        $this->assertInstanceOf(OnlineCheckWriterDocumentMail::class, $message);

        $array = $message->toArray();
        $this->assertEquals('/path/to/doc.pdf', $array['file']);
        $this->assertEquals('Test Doc', $array['documentTitle']);
        $this->assertEquals('Test User', $array['name']);
    }

    /** @test */
    public function it_can_set_metadata(): void
    {
        $metadata = ['order_id' => '12345', 'customer_id' => '67890'];

        $message = OnlineCheckWriterDocumentMail::create()
            ->metadata($metadata);

        $array = $message->toArray();

        $this->assertArrayHasKey('metadata', $array);
        $this->assertEquals($metadata, $array['metadata']);
    }
}
