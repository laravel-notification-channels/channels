<?php

namespace NotificationChannels\SmsIr\Test;

use Farsi\Smsir\Data\SendResult;
use Farsi\Smsir\Data\SingleSendResult;
use Farsi\Smsir\Enums\SmsirStatus;
use Farsi\Smsir\Exceptions\SmsirException;
use Farsi\Smsir\Smsir;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use NotificationChannels\SmsIr\Exceptions\CouldNotSendNotification;
use NotificationChannels\SmsIr\SmsIrChannel;
use NotificationChannels\SmsIr\SmsIrMessage;
use PHPUnit\Framework\TestCase;

class SmsIrChannelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @var Mockery\MockInterface&Smsir */
    protected $smsir;

    /** @var SmsIrChannel */
    protected $channel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->smsir = Mockery::mock(Smsir::class);
        $this->channel = new SmsIrChannel($this->smsir);
    }

    public function test_it_sends_a_verification_message_through_the_verify_endpoint(): void
    {
        $this->smsir->shouldReceive('verify')
            ->once()
            ->with('09120000000', 'otp', ['CODE' => '1234'])
            ->andReturn(new SingleSendResult(1, 1.0));

        $result = $this->channel->send(new TestNotifiable(), new TestOtpNotification());

        $this->assertInstanceOf(SingleSendResult::class, $result);
    }

    public function test_it_sends_a_plain_text_message_through_the_bulk_endpoint(): void
    {
        $this->smsir->shouldReceive('bulk')
            ->once()
            ->with('سفارش شما ارسال شد.', ['09120000000'], null, null)
            ->andReturn(new SendResult('pack-1', [1], 1.0));

        $result = $this->channel->send(new TestNotifiable(), new TestTextNotification());

        $this->assertInstanceOf(SendResult::class, $result);
    }

    public function test_it_accepts_a_plain_string_from_the_notification(): void
    {
        $this->smsir->shouldReceive('bulk')
            ->once()
            ->with('just a string', ['09120000000'], null, null)
            ->andReturn(new SendResult('p', [1], 1.0));

        $this->channel->send(new TestNotifiable(), new TestStringNotification());
    }

    public function test_it_sends_a_verification_to_every_recipient(): void
    {
        // The verify endpoint takes a single mobile, so a list has to be walked.
        $this->smsir->shouldReceive('verify')->once()->with('09120000000', 'otp', Mockery::any())
            ->andReturn(new SingleSendResult(1, 1.0));
        $this->smsir->shouldReceive('verify')->once()->with('09120000001', 'otp', Mockery::any())
            ->andReturn(new SingleSendResult(2, 1.0));

        $result = $this->channel->send(
            new TestNotifiable(['09120000000', '09120000001']),
            new TestOtpNotification()
        );

        $this->assertCount(2, $result);
    }

    public function test_it_skips_a_notifiable_with_no_number(): void
    {
        $this->smsir->shouldNotReceive('verify');
        $this->smsir->shouldNotReceive('bulk');

        // Not an error: a user without a phone number cannot receive this.
        $this->assertNull($this->channel->send(new TestNotifiable(null), new TestOtpNotification()));
    }

    public function test_it_falls_back_to_the_smsir_route_used_by_the_base_package(): void
    {
        $this->smsir->shouldReceive('verify')
            ->once()
            ->with('09129999999', 'otp', Mockery::any())
            ->andReturn(new SingleSendResult(1, 1.0));

        $this->channel->send(new LegacyRoutedNotifiable(), new TestOtpNotification());
    }

    public function test_it_rejects_a_notification_without_a_message_method(): void
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessageMatches('/does not define a toSmsIr/');

        $this->channel->send(new TestNotifiable(), new TestNotificationWithoutMethod());
    }

    public function test_it_rejects_an_empty_plain_text_message(): void
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessageMatches('/needs content/');

        $this->channel->send(new TestNotifiable(), new TestEmptyNotification());
    }

    public function test_it_wraps_an_upstream_error(): void
    {
        $this->smsir->shouldReceive('verify')->once()->andThrow(
            new SmsirException('کلید وب سرویس نامعتبر است', SmsirStatus::InvalidApiKey, 10, 401)
        );

        $this->expectException(CouldNotSendNotification::class);
        // The Persian message from sms.ir is the useful part; keep it intact.
        $this->expectExceptionMessageMatches('/کلید وب سرویس نامعتبر است/');

        $this->channel->send(new TestNotifiable(), new TestOtpNotification());
    }
}

class TestNotifiable
{
    use Notifiable;

    /** @var string|string[]|null */
    protected $route;

    public function __construct($route = '09120000000')
    {
        $this->route = $route;
    }

    public function routeNotificationForSmsIr()
    {
        return $this->route;
    }
}

class LegacyRoutedNotifiable
{
    use Notifiable;

    public function routeNotificationForSmsir()
    {
        return '09129999999';
    }
}

class TestOtpNotification extends Notification
{
    public function toSmsIr($notifiable)
    {
        return SmsIrMessage::verify('otp')->with(['CODE' => '1234']);
    }
}

class TestTextNotification extends Notification
{
    public function toSmsIr($notifiable)
    {
        return SmsIrMessage::create('سفارش شما ارسال شد.');
    }
}

class TestStringNotification extends Notification
{
    public function toSmsIr($notifiable)
    {
        return 'just a string';
    }
}

class TestEmptyNotification extends Notification
{
    public function toSmsIr($notifiable)
    {
        return SmsIrMessage::create();
    }
}

class TestNotificationWithoutMethod extends Notification
{
}
