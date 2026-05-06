<?php

namespace NotificationChannels\Cmsms\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Event;
use Mockery;
use NotificationChannels\TelnectSms\Events\SmsSendingFailedEvent;
use NotificationChannels\TelnectSms\Events\SmsSentSuccessfullyEvent;
use NotificationChannels\TelnectSms\Exceptions\CouldNotSendNotification;
use NotificationChannels\TelnectSms\TelnectSmsClient;
use NotificationChannels\TelnectSms\TelnectSmsMessage;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Test;

class TelnectSmsClientTest extends TestCase
{
    public static $latestResponse;

    private Client $guzzle;

    private TelnectSmsClient $client;

    private TelnectSmsMessage $message;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app['config']['services.telnect_sms.originator'] = 'My App';
        $this->guzzle = Mockery::mock(new Client);
        $this->client = Mockery::mock(new TelnectSmsClient($this->guzzle, '00000FFF-0000-F0F0-F0F0-FFFFFFFFFFFF'));
        $this->message = TelnectSmsMessage::create()->body('Message content')->sender('APPNAME');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(TelnectSmsClient::class, $this->client);
        $this->assertInstanceOf(TelnectSmsMessage::class, $this->message);
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function it_can_send_message()
    {
        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], '{"details": "Created 1 message(s)", "status": 1}'));

        $this->client->send($this->message, '00301234');
    }

    #[Test]
    #[DoesNotPerformAssertions]
    public function it_sets_a_default_originator_if_none_is_set()
    {
        $message = Mockery::mock(TelnectSmsMessage::create()->body('Message body'));
        $message->shouldReceive('originator')
            ->once()
            ->with($this->app['config']['services.telnect_sms.originator']);

        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], '{"status": 1,"to": "+46763809905","from": "+46763809900","reference": 82759143,"messages": 2}'));

        $this->client->send($message, '00301234');
    }

    #[Test]
    public function it_throws_exception_on_error_response()
    {
        $this->expectException(CouldNotSendNotification::class);

        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], '{"status": 22,"to": "+46763809905","from": "+46763809900","reference": 82759143,"messages": 2}'));

        $this->client->send($this->message, '00301234');
    }

    #[Test]
    public function it_dispatches_a_success_event()
    {
        Event::fake();

        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], '{"status": 1,"to": "+46763809905","from": "+46763809900","reference": 82759143,"messages": 2}'));

        $this->client->send($this->message, '00301234');

        Event::assertDispatched(SmsSentSuccessfullyEvent::class);
    }

    #[Test]
    public function it_dispatches_a_failure_event()
    {
        Event::fake();

        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], '{"status": 31,"to": "+46763809905","from": "+46763809900","reference": 82759143,"messages": 2}'));

        try {
            $this->client->send($this->message, '00301234');
        } catch (CouldNotSendNotification $e) {
            // Do nothing, we know about the exception
        }

        Event::assertDispatched(SmsSendingFailedEvent::class);
    }
}
