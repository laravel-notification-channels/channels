<?php

namespace NotificationChannels\Cmsms\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use NotificationChannels\Cmsms\CmsmsClient;
use NotificationChannels\Cmsms\CmsmsMessage;
use NotificationChannels\Cmsms\Exceptions\CouldNotSendNotification;
use PHPUnit_Framework_TestCase;

class CmsmsClientTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->guzzle = Mockery::mock(new Client());
        $this->client = Mockery::mock(new CmsmsClient($this->guzzle, '00000FFF-0000-F0F0-F0f0-FFFFFFFFFFFF'));
        $this->message = (new CmsmsMessage('Message content'))->setOriginator('APPNAME')->setRecipient('0031612345678');
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(CmsmsClient::class, $this->client);
        $this->assertInstanceOf(CmsmsMessage::class, $this->message);
    }

    /** @test */
    public function it_can_send_message()
    {
        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], 'error'));

        $this->client->send($this->message);
    }

    /** @test */
    public function it_throws_exception_on_error_response()
    {
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], 'error'));

        $this->client->send($this->message);
    }
}
