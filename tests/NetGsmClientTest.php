<?php

namespace NotificationChannels\Messagebird\Test;

use GuzzleHttp\Client;
use Mockery;
use NotificationChannels\NetGsm\NetGsmClient;
use NotificationChannels\NetGsm\NetGsmMessage;
use PHPUnit\Framework\TestCase;

class NetGsmClientTest extends TestCase
{
    /** @var NetGsmClient */
    protected $client;

    /** @var Client */
    protected $guzzle;

    /** @var NetGsmMessage */
    protected $message;

    public function setUp(): void
    {
        $this->guzzle = Mockery::mock(new Client());

        $userCode = '5458886155';
        $secret = '578BEA';

        $this->client = Mockery::mock(new NetGsmClient($this->guzzle, $userCode, $secret));
        $this->message = (new NetGsmMessage('Message content'))
            ->setRecipients('5984196155')
            ->setHeader('COMPANY');
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(NetGsmClient::class, $this->client);
        $this->assertInstanceOf(NetGsmMessage::class, $this->message);
    }

    /** @test */
    public function it_can_send_message()
    {
        $this->expectExceptionMessage('30: Invalid API credentials');

        $this->client->send($this->message);
    }
}
