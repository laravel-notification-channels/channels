<?php

namespace NotificationChannels\TurboSMS;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use PHPUnit\Framework\TestCase;

class TurboSMSChannelTest extends TestCase
{
    /**
     * @var TurboSMSChannel|Mockery\MockInterface
     */
    protected $testChannel;

    /**
     * @var array
     */
    protected $testConfig;

    /**
     * @var Notification|Mockery\MockInterface
     */
    protected $testNotification;

    protected function setUp(): void
    {
        $this->testConfig = [
            'login' => 'TEST_LOGIN',
            'password' => 'TEST_PASSWORD',
            'wsdl_endpoint' => 'http://turbosms.in.ua/api/wsdl.html',
            'sender' => 'TEST_SENDER',
            'debug' => false,
            'sandbox_mode' => false,
        ];
        $this->testChannel = Mockery::mock(TurboSMSChannel::class, [$this->testConfig])->makePartial()->shouldAllowMockingProtectedMethods();

        $testClient = Mockery::mock(\SoapClient::class);
        $testClient->shouldReceive('Auth')->andReturn(true);
        $testClient->shouldReceive('SendSMS')->andReturn([
            'Messages were successfully sent',
            '304a2914-5e1f-772d-9e2f-527a12e01c11',
        ]);

        $this->testChannel->shouldReceive('getClient')->andReturn($testClient);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_can_be_instantiate()
    {
        $instance = new TurboSMSChannel($this->testConfig);
        $this->assertInstanceOf(TurboSMSChannel::class, $instance);
    }

    /** @test */
    public function it_sends_a_notification()
    {
        $this->assertIsArray($this->testChannel->send(new TestNotifiable(), new TestNotification()));
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForTurboSMS()
    {
        return 'TEST_RECIPIENT';
    }
}

class TestNotification extends Notification
{
    public function toTurboSMS($notifiable)
    {
        return new TurboSMSMessage('TEST_BODY');
    }
}
