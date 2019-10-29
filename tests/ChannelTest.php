<?php

namespace NotificationChannels\AllMySms\Test;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use NotificationChannels\AllMySms\AllMySms;
use NotificationChannels\AllMySms\AllMySmsChannel;
use NotificationChannels\AllMySms\Exceptions\CouldNotSendNotification;
use NotificationChannels\AllMySms\Test\Notifications\TestNotification;
use NotificationChannels\AllMySms\Test\Notifications\TestNotificationWithParameters;

class ChannelTest extends TestCase
{
    /** @var \GuzzleHttp\Client|\Mockery\LegacyMockInterface|\Mockery\MockInterface */
    protected $http;

    /**
     * @var array
     */
    protected $config;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->http = Mockery::mock(Client::class);
        $this->config = [
            'uri' => 'https://domain.tld',
            'login' => 'login',
            'api_key' => 'key',
            'format' => 'json',
        ];
    }

    /** @test */
    public function sms_is_sent_via_all_my_sms()
    {
        $notifiable = new TestNotifiable;
        $notification = new TestNotification;

        $channel = new AllMySmsChannel(
            $client = Mockery::mock(AllMySms::class), '0600000000'
        );

        $client->shouldReceive('sendSms')
            ->once()
            ->with('0602030405', [
                'message' => 'Sms content',
                'sender' => null,
                'campaign' => null,
                'date' => null,
                'parameters' => null,
            ], '0600000000')
            ->andReturn(new Response(200));

        $channel->send($notifiable, $notification);
    }

    /** @test */
    public function sms_is_sent_with_parameters()
    {
        $notifiable = new TestNotifiable;
        $notification = new TestNotificationWithParameters;

        $channel = new AllMySmsChannel(
            new AllMySms($this->http, $this->config)
        );

        $this->http->shouldReceive('post')
            ->once()
            ->with('https://domain.tld/sendSms', [
                'form_params' => [
                    'login' => 'login',
                    'apiKey' => 'key',
                    'returnformat' => 'json',
                    'smsData' => json_encode([
                        'DATA' => [
                            'MESSAGE' => 'Hello #param_1#',
                            'SMS' => [
                                [
                                    'MOBILEPHONE' => '0602030405',
                                    'PARAM_1' => 'world',
                                ],
                            ],
                            'DYNAMIC' => 1,
                        ],
                    ]),
                ],
            ])
            ->andReturn(new Response(200, [], json_encode([
                'status' => 100,
                'statusText' => 'Ok',
            ])));

        $channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_throws_an_exception_when_the_client_response_is_not_ok()
    {
        $this->expectException(CouldNotSendNotification::class);

        $notifiable = new TestNotifiable;
        $notification = new TestNotification;

        $channel = new AllMySmsChannel(
            new AllMySms($this->http, $this->config)
        );

        $this->http->shouldReceive('post')
            ->once()
            ->andReturn(new Response(500));

        $channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_throws_an_exception_when_the_client_response_status_is_not_100()
    {
        $this->expectException(CouldNotSendNotification::class);

        $notifiable = new TestNotifiable;
        $notification = new TestNotification;

        $channel = new AllMySmsChannel(
            new AllMySms($this->http, $this->config)
        );

        $this->http->shouldReceive('post')
            ->once()
            ->andReturn(new Response(200, [], json_encode([
                'status' => 101,
                'statusText' => 'An error occured',
            ])));

        $channel->send($notifiable, $notification);
    }
}
