<?php

namespace NotificationChannels\Unifonic\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use Mockery;
use NotificationChannels\Unifonic\UnifonicClient;
use NotificationChannels\Unifonic\UnifonicMessage;
use NotificationChannels\Unifonic\Exceptions\CouldNotSendNotification;
use Orchestra\Testbench\TestCase;

class UnifonicClientTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->appsId = '1335b3f7-ba23-4316-b2dd-202448bd8904';
        $this->guzzle =Mockery::mock(new Client());
        $this->client = Mockery::mock(new UnifonicClient($this->guzzle, $this->appsId));
        $this->message = UnifonicMessage::create('Message body');
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(UnifonicClient::class, $this->client);
        $this->assertInstanceOf(UnifonicMessage::class, $this->message);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function it_can_send_message()
    {
        $this->guzzle
            ->shouldReceive('request')
            ->once()
            ->andReturn(new Response(200, [], ''));
        $this->client->send($this->message, '212679');
    }


    /** @test */
    public function it_throws_exception_on_error_response()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->guzzle
        ->shouldReceive('request')
        ->once()
        ->andReturn(new Response(200, [], '{"success": "false"}'));
        $this->client->send($this->message, '212679');
    }
}
