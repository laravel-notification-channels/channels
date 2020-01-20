<?php

namespace NotificationChannels\TurboSMS;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use PHPUnit\Framework\TestCase;

class TurboSMSChannelTest extends TestCase
{
    /**
     * @var Notification|Mockery\MockInterface
     */
    protected $testNotification;

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_can_be_instantiate()
    {
        $testConfig = [
            'login' => 'TEST_LOGIN',
            'password' => 'TEST_PASSWORD',
            'wsdlEndpoint' => 'http://turbosms.in.ua/api/wsdl.html',
            'sender' => 'TEST_SENDER',
            'debug' => false,
            'sandboxMode' => false,
        ];
        $instance = new TurboSMSChannel($testConfig);

        $this->assertInstanceOf(TurboSMSChannel::class, $instance);
    }

    /** @test */
    public function it_sends_a_notification()
    {
        $testConfig = [
            'login' => 'TEST_LOGIN',
            'password' => 'TEST_PASSWORD',
            'wsdlEndpoint' => 'http://turbosms.in.ua/api/wsdl.html',
            'sender' => 'TEST_SENDER',
            'debug' => false,
            'sandboxMode' => false,
        ];

        $testClient = Mockery::mock(\SoapClient::class);
        $testClient->shouldReceive('Auth')->andReturn(true);
        $testClient->shouldReceive('SendSMS')->andReturn([
            'Messages were successfully sent',
            '304a2914-5e1f-772d-9e2f-527a12e01c11',
        ]);

        $testChannel = Mockery::mock(TurboSMSChannel::class, [$testConfig])->makePartial()->shouldAllowMockingProtectedMethods();
        $testChannel->shouldReceive('getClient')->andReturn($testClient);

        $this->assertIsArray($testChannel->send(new TestNotifiable(), new TestNotification()));
    }

    /** @test */
    public function it_do_not_invoke_api_in_sandbox_mode()
    {
        $testConfig = [
            'login' => 'TEST_LOGIN',
            'password' => 'TEST_PASSWORD',
            'wsdlEndpoint' => 'http://turbosms.in.ua/api/wsdl.html',
            'sender' => 'TEST_SENDER',
            'debug' => false,
            'sandboxMode' => true,
        ];

        $testClient = Mockery::spy(\SoapClient::class);

        $testChannel = Mockery::mock(TurboSMSChannel::class, [$testConfig])->makePartial()->shouldAllowMockingProtectedMethods();
        $testChannel->shouldReceive('getClient')->andReturn($testClient);

        $this->assertNull($testChannel->send(new TestNotifiable(), new TestNotification()));
        $testClient->shouldNotHaveReceived('SendSMS');
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
