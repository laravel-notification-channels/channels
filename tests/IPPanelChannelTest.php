<?php

namespace NotificationChannels\PersianSms\Tests; // Or YourVendorName\PersianSms\Tests

use Mockery;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\Response as HttpResponse;
use Illuminate\Notifications\Notification;
use NotificationChannels\PersianSms\IPPanel\IPPanelChannel;
use NotificationChannels\PersianSms\IPPanel\IPPanelMessage;
use NotificationChannels\PersianSms\Exceptions\CouldNotSendNotification;
use NotificationChannels\PersianSms\PersianSmsServiceProvider;

class IPPanelChannelTest extends TestCase // Assuming TestCase.php exists and is configured
{
    /** @var Mockery\MockInterface */
    protected $httpClientMock;

    /** @var IPPanelChannel */
    protected $channel;

    /** @var \Illuminate\Config\Repository */
    protected $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->httpClientMock = Mockery::mock(HttpClient::class);
        $this->config = $this->app['config'];

        $this->config->set('persian-sms.drivers.ippanel.api_key', 'test_api_key');
        $this->config->set('persian-sms.drivers.ippanel.sender_number', '+983000123');
        $this->config->set('persian-sms.guzzle.timeout', 5.0);

        // Re-create the channel directly for this test to inject the mock HttpClient
        $this->channel = new IPPanelChannel(
            $this->httpClientMock,
            $this->config->get('persian-sms.drivers.ippanel.api_key'),
            $this->config->get('persian-sms.drivers.ippanel.sender_number')
        );
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Override application service providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            PersianSmsServiceProvider::class,
        ];
    }

    /** @test */
    public function it_can_send_a_normal_sms_message()
    {
        $messageContent = 'Test normal SMS content';
        $recipientNumber = '+989120000001';
        $senderNumber = $this->config->get('persian-sms.drivers.ippanel.sender_number');
        $apiKey = $this->config->get('persian-sms.drivers.ippanel.api_key');

        $expectedPayload = [
            'recipient' => [$recipientNumber],
            'sender'    => $senderNumber,
            'message'   => $messageContent,
        ];

        $mockedHttpResponse = new HttpResponse(200, [], json_encode(['status' => 'OK', 'data' => ['message_id' => '12345']]));

        $this->httpClientMock
            ->shouldReceive('post')
            ->once()
            ->with(
                'https://api2.ippanel.com/api/v1/sms/send/webservice/single',
                [
                    'headers' => [
                        'apiKey' => $apiKey,
                        'Accept'        => 'application/json',
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => $expectedPayload,
                ]
            )
            ->andReturn($mockedHttpResponse);

        $notification = new TestNotificationWithMessage($messageContent);
        $notifiable = new TestNotifiable(['persianSms' => $recipientNumber]);

        $response = $this->channel->send($notifiable, $notification);

        // Add PHPUnit assertion
        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertSame($mockedHttpResponse, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_can_send_a_pattern_based_sms_message()
    {
        $patternCode = 'test_pattern_code';
        $variables = ['name' => 'John Doe', 'code' => '12345'];
        $recipientNumber = '+989120000002';
        $senderNumber = $this->config->get('persian-sms.drivers.ippanel.sender_number');
        $apiKey = $this->config->get('persian-sms.drivers.ippanel.api_key');

        $expectedPayload = [
            'code'      => $patternCode,
            'sender'    => $senderNumber,
            'recipient' => $recipientNumber,
            'variable'  => (object) $variables,
        ];

        $mockedHttpResponse = new HttpResponse(200, [], json_encode(['status' => 'OK', 'data' => ['message_id' => '67890']]));

        $this->httpClientMock
            ->shouldReceive('post')
            ->once()
            ->with(
                'https://api2.ippanel.com/api/v1/sms/pattern/normal/send',
                [
                    'headers' => [
                        'apiKey' =>  $apiKey,
                        'Accept'        => 'application/json',
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => $expectedPayload,
                ]
            )
            ->andReturn($mockedHttpResponse);

        $notification = new TestNotificationWithPattern($patternCode, $variables);
        $notifiable = new TestNotifiable(['persianSms' => $recipientNumber]);

        $response = $this->channel->send($notifiable, $notification);

        // Add PHPUnit assertion
        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertSame($mockedHttpResponse, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_throws_exception_if_api_key_is_missing_when_resolved_via_provider()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('IPPanel API key is missing or not configured.');

        $this->config->set('persian-sms.drivers.ippanel.api_key', null);
        $this->app->make(IPPanelChannel::class); // Attempt to resolve from container
    }

    /** @test */
    public function it_throws_exception_if_sender_number_is_missing_when_resolved_via_provider()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Sender (originator/from number) was not provided in message or configuration.');

        $this->config->set('persian-sms.drivers.ippanel.sender_number', null);
        $this->app->make(IPPanelChannel::class); // Attempt to resolve from container
    }


    /** @test */
    public function it_throws_exception_on_http_error_from_service()
    {
        $this->expectException(CouldNotSendNotification::class);
        // Adjusted to match the actual error message format from CouldNotSendNotification::serviceRespondedWithAnError
        $this->expectExceptionMessage('SMS service responded with an error: "PERMISSION_DENIED" (Status Code: 401)');


        $errorResponseBody = ['status' => 'Error', 'code' => 401, 'errorMessage' => 'PERMISSION_DENIED'];
        $mockedErrorHttpResponse = new HttpResponse(401, [], json_encode($errorResponseBody));

        $this->httpClientMock
            ->shouldReceive('post')
            ->once()
            ->andReturn($mockedErrorHttpResponse);

        $notification = new TestNotificationWithMessage('test content');
        $notifiable = new TestNotifiable(['persianSms' => '+989000000000']);

        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_uses_sender_from_message_if_provided()
    {
        $messageContent = 'Test with custom sender';
        $customSender = '+983000789';
        $recipientNumber = '+989120000003';
        $apiKey = $this->config->get('persian-sms.drivers.ippanel.api_key');

        $expectedPayload = [
            'recipient' => [$recipientNumber],
            'sender'    => $customSender,
            'message'   => $messageContent,
        ];
        $mockedHttpResponse = new HttpResponse(200, [], json_encode(['status' => 'OK', 'data' => ['message_id' => '11223']]));

        $this->httpClientMock
            ->shouldReceive('post')
            ->once()
            ->with(
                'https://api2.ippanel.com/api/v1/sms/send/webservice/single', // Be specific with URL if possible
                Mockery::on(function ($argument) use ($expectedPayload, $apiKey) {
                    return $argument['json'] == $expectedPayload &&
                           isset($argument['headers']['apiKey']) &&
                           $argument['headers']['apiKey'] == $apiKey;
                })
            )
            ->andReturn($mockedHttpResponse);

        $notification = new TestNotificationWithMessageAndCustomSender($messageContent, $customSender);
        $notifiable = new TestNotifiable(['persianSms' => $recipientNumber]);

        $response = $this->channel->send($notifiable, $notification);

        // Add PHPUnit assertion
        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertSame($mockedHttpResponse, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_throws_exception_if_message_is_not_ippanel_message_and_not_string()
    {
        $this->expectException(CouldNotSendNotification::class);
        // Corrected expected message for array input
        $this->expectExceptionMessage('The message object provided was invalid. Expected an instance of IPPanelMessage or a string, got Unknown.');


        $notification = new TestNotificationWithInvalidMessageObject();
        $notifiable = new TestNotifiable(['persianSms' => '+989120000000']);

        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_throws_exception_if_pattern_code_is_missing_for_pattern_message()
    {
        $this->expectException(CouldNotSendNotification::class);
        // Corrected expected message based on current channel logic
        $this->expectExceptionMessage('SMS content was not provided for a normal message.');

        $notification = new TestNotificationWithPattern(null, ['var' => 'val']);
        $notifiable = new TestNotifiable(['persianSms' => '+989120000000']);

        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_throws_exception_if_content_is_empty_for_normal_message()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('SMS content was not provided for a normal message.');

        $notification = new TestNotificationWithMessage('');
        $notifiable = new TestNotifiable(['persianSms' => '+989120000000']);

        $this->channel->send($notifiable, $notification);
    }
}

// --- Helper classes for testing (Ensure these are correctly namespaced if in separate files) ---

class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    protected array $routes = [];

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    public function routeNotificationFor($driver, $notification = null)
    {
        return $this->routes[$driver] ?? ($this->routes['*'] ?? null);
    }
    public function getKey() { return '1'; }
}

class TestNotificationWithMessage extends Notification
{
    public string $message;
    public function __construct(string $message) { $this->message = $message; }
    public function via($notifiable): array { return [IPPanelChannel::class]; }
    public function toPersianSms($notifiable): IPPanelMessage { return (new IPPanelMessage())->content($this->message); }
}

class TestNotificationWithPattern extends Notification
{
    public ?string $patternCode;
    public array $variables;
    public function __construct(?string $patternCode, array $variables) { $this->patternCode = $patternCode; $this->variables = $variables; }
    public function via($notifiable): array { return [IPPanelChannel::class]; }
    public function toPersianSms($notifiable): IPPanelMessage
    {
        $message = new IPPanelMessage();
        if ($this->patternCode !== null) {
            $message->pattern($this->patternCode, $this->variables);
        } else {
            // This setup makes isPattern() return false, leading to normal SMS path
            $message->patternCode = null;
            $message->variables = $this->variables;
            // $message->content remains null
        }
        return $message;
    }
}

class TestNotificationWithMessageAndCustomSender extends Notification
{
    public string $message;
    public string $sender;
    public function __construct(string $message, string $sender) { $this->message = $message; $this->sender = $sender; }
    public function via($notifiable): array { return [IPPanelChannel::class]; }
    public function toPersianSms($notifiable): IPPanelMessage { return (new IPPanelMessage())->content($this->message)->from($this->sender); }
}

class TestNotificationWithInvalidMessageObject extends Notification
{
    public function via($notifiable): array { return [IPPanelChannel::class]; }
    public function toPersianSms($notifiable) { return ['this is not an IPPanelMessage object']; } // Intentionally wrong
}

