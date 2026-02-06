<?php

namespace Zilmoney\OnlineCheckWriter\Tests\Unit;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Zilmoney\OnlineCheckWriter\Channel\OnlineCheckWriterChannel;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterDocumentMail;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterMailCheck;
use Zilmoney\OnlineCheckWriter\OnlineCheckWriterClient;

class ChannelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_send_document_mail_notification(): void
    {
        $client = Mockery::mock(OnlineCheckWriterClient::class);
        $client->shouldReceive('send')
            ->once()
            ->andReturn(['success' => true, 'data' => ['id' => 'mail-123']]);

        $channel = new OnlineCheckWriterChannel($client);

        $notifiable = new TestNotifiable();
        $notification = new TestDocumentMailNotification();

        $result = $channel->send($notifiable, $notification);

        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
        $this->assertEquals('mail-123', $result['data']['id']);
    }

    /** @test */
    public function it_can_send_mail_check_notification(): void
    {
        $client = Mockery::mock(OnlineCheckWriterClient::class);
        $client->shouldReceive('send')
            ->once()
            ->andReturn(['success' => true, 'data' => ['id' => 'check-456']]);

        $channel = new OnlineCheckWriterChannel($client);

        $notifiable = new TestNotifiable();
        $notification = new TestMailCheckNotification();

        $result = $channel->send($notifiable, $notification);

        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
        $this->assertEquals('check-456', $result['data']['id']);
    }

    /** @test */
    public function it_returns_null_for_invalid_message(): void
    {
        $client = Mockery::mock(OnlineCheckWriterClient::class);
        $client->shouldNotReceive('send');

        $channel = new OnlineCheckWriterChannel($client);

        $notifiable = new TestNotifiable();
        $notification = new TestInvalidNotification();

        $result = $channel->send($notifiable, $notification);

        $this->assertNull($result);
    }

    /** @test */
    public function it_extracts_recipient_from_route_notification_method(): void
    {
        $client = Mockery::mock(OnlineCheckWriterClient::class);
        $client->shouldReceive('send')
            ->once()
            ->withArgs(function ($message) {
                $array = $message->toArray();
                return $array['name'] === 'Route Name' && $array['city'] === 'Route City';
            })
            ->andReturn(['success' => true]);

        $channel = new OnlineCheckWriterChannel($client);

        $notifiable = new TestNotifiableWithRoute();
        $notification = new TestDocumentMailNotificationWithoutRecipient();

        $channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_extracts_recipient_from_postal_address_method(): void
    {
        $client = Mockery::mock(OnlineCheckWriterClient::class);
        $client->shouldReceive('send')
            ->once()
            ->withArgs(function ($message) {
                $array = $message->toArray();
                return $array['name'] === 'Postal Name' && $array['city'] === 'Postal City';
            })
            ->andReturn(['success' => true]);

        $channel = new OnlineCheckWriterChannel($client);

        $notifiable = new TestNotifiableWithPostalAddress();
        $notification = new TestDocumentMailNotificationWithoutRecipient();

        $channel->send($notifiable, $notification);
    }
}

// Test helpers

class TestNotifiable
{
    use Notifiable;

    public string $name = 'Test User';
    public string $email = 'test@example.com';
}

class TestNotifiableWithRoute
{
    use Notifiable;

    public function routeNotificationForOnlineCheckWriter(): array
    {
        return [
            'name' => 'Route Name',
            'address1' => '123 Route St',
            'city' => 'Route City',
            'state' => 'TX',
            'zip' => '75001',
        ];
    }
}

class TestNotifiableWithPostalAddress
{
    use Notifiable;

    public function getPostalAddress(): array
    {
        return [
            'name' => 'Postal Name',
            'address1' => '456 Postal Ave',
            'city' => 'Postal City',
            'state' => 'CA',
            'zip' => '90001',
        ];
    }
}

class TestDocumentMailNotification extends Notification
{
    public function toOnlineCheckWriter($notifiable): OnlineCheckWriterDocumentMail
    {
        return OnlineCheckWriterDocumentMail::create()
            ->attachmentUrl('https://example.com/doc.pdf')
            ->documentTitle('Test Document')
            ->name('Test Recipient')
            ->address1('123 Test St')
            ->city('Test City')
            ->state('TX')
            ->zip('75001');
    }
}

class TestDocumentMailNotificationWithoutRecipient extends Notification
{
    public function toOnlineCheckWriter($notifiable): OnlineCheckWriterDocumentMail
    {
        return OnlineCheckWriterDocumentMail::create()
            ->attachmentUrl('https://example.com/doc.pdf')
            ->documentTitle('Test Document');
    }
}

class TestMailCheckNotification extends Notification
{
    public function toOnlineCheckWriter($notifiable): OnlineCheckWriterMailCheck
    {
        return OnlineCheckWriterMailCheck::create()
            ->bankAccount('test-bank-account')
            ->amount(500)
            ->memo('Test Payment')
            ->issueDate('2026-01-28')
            ->name('Test Recipient')
            ->address1('123 Test St')
            ->city('Test City')
            ->state('TX')
            ->zip('75001');
    }
}

class TestInvalidNotification extends Notification
{
    public function toOnlineCheckWriter($notifiable): string
    {
        return 'invalid';
    }
}
