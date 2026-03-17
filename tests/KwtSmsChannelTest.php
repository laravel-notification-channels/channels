<?php

namespace NotificationChannels\KwtSms\Tests;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use KwtSMS\KwtSMS;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NotificationChannels\KwtSms\Exceptions\CouldNotSendNotification;
use NotificationChannels\KwtSms\KwtSmsChannel;
use NotificationChannels\KwtSms\KwtSmsMessage;
use Orchestra\Testbench\TestCase;

class KwtSmsChannelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected KwtSMS|Mockery\MockInterface $client;

    protected KwtSmsChannel $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Mockery::mock(KwtSMS::class);
        $this->channel = new KwtSmsChannel($this->client);
    }

    public function test_it_can_send_notification(): void
    {
        $this->client
            ->shouldReceive('send')
            ->once()
            ->with('96598765432', 'Test message', 'KWT-SMS')
            ->andReturn(['result' => 'OK', 'msg-id' => '12345']);

        $response = $this->channel->send(new TestNotifiable(), new TestNotification());

        $this->assertSame('OK', $response['result']);
    }

    public function test_it_does_not_send_when_content_is_empty(): void
    {
        $this->client->shouldNotReceive('send');

        $response = $this->channel->send(new TestNotifiable(), new TestEmptyNotification());

        $this->assertNull($response);
    }

    public function test_it_does_not_send_when_no_phone_number(): void
    {
        $this->client->shouldNotReceive('send');

        $response = $this->channel->send(new TestNotifiableWithoutPhone(), new TestNotification);

        $this->assertNull($response);
    }

    public function test_it_throws_on_api_error(): void
    {
        $this->client
            ->shouldReceive('send')
            ->once()
            ->andReturn(['result' => 'ERROR', 'code' => 'ERR001', 'description' => 'Invalid credentials']);

        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('kwtSMS API error [ERR001]: Invalid credentials');

        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    public function test_it_accepts_string_message(): void
    {
        $this->client
            ->shouldReceive('send')
            ->once()
            ->with('96598765432', 'String message', 'KWT-SMS')
            ->andReturn(['result' => 'OK']);

        $response = $this->channel->send(new TestNotifiable(), new TestStringNotification());

        $this->assertSame('OK', $response['result']);
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForKwtSms(): string
    {
        return '96598765432';
    }
}

class TestNotifiableWithoutPhone
{
    use Notifiable;

    public function routeNotificationForKwtSms(): string
    {
        return '';
    }
}

class TestNotification extends Notification
{
    public function via($notifiable): array
    {
        return [KwtSmsChannel::class];
    }

    public function toKwtSms($notifiable): KwtSmsMessage
    {
        return KwtSmsMessage::create('Test message');
    }
}

class TestEmptyNotification extends Notification
{
    public function via($notifiable): array
    {
        return [KwtSmsChannel::class];
    }

    public function toKwtSms($notifiable): KwtSmsMessage
    {
        return KwtSmsMessage::create('');
    }
}

class TestStringNotification extends Notification
{
    public function via($notifiable): array
    {
        return [KwtSmsChannel::class];
    }

    public function toKwtSms($notifiable): string
    {
        return 'String message';
    }
}
