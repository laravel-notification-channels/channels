<?php

namespace Zilmoney\OnlineCheckWriter\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterMailCheck;

class MailCheckMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_created_with_static_method(): void
    {
        $message = OnlineCheckWriterMailCheck::create();

        $this->assertInstanceOf(OnlineCheckWriterMailCheck::class, $message);
    }

    /** @test */
    public function it_can_set_bank_account(): void
    {
        $message = OnlineCheckWriterMailCheck::create()
            ->bankAccount('bank-account-123');

        $array = $message->toArray();

        $this->assertEquals('bank-account-123', $array['bankAccountId']);
    }

    /** @test */
    public function it_can_set_amount(): void
    {
        $message = OnlineCheckWriterMailCheck::create()
            ->amount(500.50);

        $array = $message->toArray();

        $this->assertEquals(500.50, $array['amount']);
    }

    /** @test */
    public function it_can_set_memo(): void
    {
        $message = OnlineCheckWriterMailCheck::create()
            ->memo('Payment for Invoice #1234');

        $array = $message->toArray();

        $this->assertEquals('Payment for Invoice #1234', $array['memo']);
    }

    /** @test */
    public function it_can_set_note(): void
    {
        $message = OnlineCheckWriterMailCheck::create()
            ->note('Internal reference: REF-001');

        $array = $message->toArray();

        $this->assertEquals('Internal reference: REF-001', $array['note']);
    }

    /** @test */
    public function it_can_set_issue_date(): void
    {
        $message = OnlineCheckWriterMailCheck::create()
            ->issueDate('2026-01-28');

        $array = $message->toArray();

        $this->assertEquals('2026-01-28', $array['issueDate']);
    }

    /** @test */
    public function it_can_set_recipient_details_individually(): void
    {
        $message = OnlineCheckWriterMailCheck::create()
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

        $message = OnlineCheckWriterMailCheck::create()
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
    public function it_can_set_shipping_type(): void
    {
        $message = OnlineCheckWriterMailCheck::create()
            ->shippingType(2);

        $array = $message->toArray();

        $this->assertEquals(2, $array['shippingTypeId']);
    }

    /** @test */
    public function it_has_default_shipping_type(): void
    {
        $message = OnlineCheckWriterMailCheck::create();

        $array = $message->toArray();

        $this->assertEquals(1, $array['shippingTypeId']);
    }

    /** @test */
    public function it_can_set_account_type(): void
    {
        $message = OnlineCheckWriterMailCheck::create()
            ->accountType('checking');

        $array = $message->toArray();

        $this->assertEquals('checking', $array['accountType']);
    }

    /** @test */
    public function it_has_default_account_type(): void
    {
        $message = OnlineCheckWriterMailCheck::create();

        $array = $message->toArray();

        $this->assertEquals('bankaccount', $array['accountType']);
    }

    /** @test */
    public function it_supports_fluent_interface(): void
    {
        $message = OnlineCheckWriterMailCheck::create()
            ->bankAccount('bank-123')
            ->amount(1000)
            ->memo('Test Payment')
            ->issueDate('2026-01-28')
            ->name('Test User')
            ->city('Test City')
            ->state('TX')
            ->zip('75001')
            ->shippingType(1);

        $this->assertInstanceOf(OnlineCheckWriterMailCheck::class, $message);

        $array = $message->toArray();
        $this->assertEquals('bank-123', $array['bankAccountId']);
        $this->assertEquals(1000, $array['amount']);
        $this->assertEquals('Test Payment', $array['memo']);
    }

    /** @test */
    public function it_can_build_complete_check_payload(): void
    {
        $message = OnlineCheckWriterMailCheck::create()
            ->bankAccount('bank-account-id')
            ->amount(500)
            ->memo('Invoice Payment')
            ->note('Internal note')
            ->issueDate('2026-01-28')
            ->name('Recipient Name')
            ->company('Recipient Co')
            ->address1('123 Street')
            ->city('City')
            ->state('ST')
            ->zip('12345')
            ->phone('1234567890')
            ->email('test@example.com')
            ->shippingType(3);

        $array = $message->toArray();

        $this->assertArrayHasKey('bankAccountId', $array);
        $this->assertArrayHasKey('amount', $array);
        $this->assertArrayHasKey('memo', $array);
        $this->assertArrayHasKey('note', $array);
        $this->assertArrayHasKey('issueDate', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('company', $array);
        $this->assertArrayHasKey('address1', $array);
        $this->assertArrayHasKey('city', $array);
        $this->assertArrayHasKey('state', $array);
        $this->assertArrayHasKey('zip', $array);
        $this->assertArrayHasKey('phone', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayHasKey('shippingTypeId', $array);
    }
}
