<?php

namespace NotificationChannels\SparrowSMS;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use PHPUnit\Framework\TestCase;

class SparrowSMSChannelTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiate()
    {
        $testConfig = [
	        'endpoint' => 'http://api.sparrowsms.com/v2/sms/?',
	        'token' => 'TEST_TOKEN',
	        'from' => 'TEST_FROM',
	    ];

        $instance = new SparrowSMSChannel($testConfig);

        $this->assertInstanceOf(SparrowSMSChannel::class, $instance);
    }

    /** @test */
    public function it_can_send_a_sms_notification()
    {
        $testConfig = [
	        'endpoint' => 'http://api.sparrowsms.com/v2/sms/?',
	        'token' => 'TEST_TOKEN',
	        'from' => 'TEST_FROM',
	    ];

        $testChannel = Mockery::mock(SparrowSMSChannel::class, $testConfig);

        $testChannel->shouldReceive('send')
            ->andReturn($testConfig);

        $this->assertIsArray($testChannel->send(new TestNotifiable(), new TestNotification()));
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForSparrowSMS()
    {
        return 'TEST_RECIPIENT';
    }
}

class TestNotification extends Notification
{
    public function toSparrowSMS($notifiable)
    {
        return new SparrowSMSMessage('TEST_BODY');
    }
}