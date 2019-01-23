<?php

namespace NotificationChannels\Messagebird\Test;

use GuzzleHttp\Client;
use Mockery;
use NotificationChannels\NetGsm\NetGsmClient;
use NotificationChannels\NetGsm\NetGsmMessage;
use PHPUnit_Framework_TestCase;

class NetGsmClientTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->guzzle = Mockery::mock(new Client());

        $userCode = "5458886155";
        $secret = "578BEA";

        $this->client = Mockery::mock(new NetGsmClient($this->guzzle, $userCode, $secret));
        $this->message = (new NetGsmMessage('Message content'))
            ->setRecipients("5984196155")
            ->setHeader('COMPANY');
    }

    public function tearDown()
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
        $this->client->send($this->message);
    }
}