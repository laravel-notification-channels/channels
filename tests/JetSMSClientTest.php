<?php
/**
 * Author: Hilmi Erdem KEREN
 * Date: 17/11/2016
 * Time: 23:06.
 */
namespace NotificationChannels\JetSMS\Test;

use Mockery as M;
use GuzzleHttp\Client;
use NotificationChannels\JetSMS\Exceptions\CouldNotBootClient;
use Psr\Http\Message\MessageInterface;
use NotificationChannels\JetSMS\JetSMSMessage;
use NotificationChannels\JetSMS\Clients\Http\JetSMSClient;
use NotificationChannels\JetSMS\Exceptions\CouldNotSendNotification;

class JetSMSClientTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $httpClient;
    private $httpMessage;
    private $clientWithNoPredefinedOriginator;

    public function setUp()
    {
        parent::setUp();

        $this->httpClient = M::mock(Client::class);
        $this->httpMessage = M::mock(MessageInterface::class);
        $this->client = new JetSMSClient($this->httpClient, 'foo', 'bar', 'baz', 'qux');
        $this->clientWithNoPredefinedOriginator = new JetSMSClient($this->httpClient, 'foo', 'bar', 'baz');
    }

    public function tearDown()
    {
        M::close();

        parent::tearDown();
    }

    /** @test */
    public function it_should_add_a_message_to_request_and_send_it()
    {
        $this->httpClient->shouldReceive('request')
                         ->once()
                         ->andReturn($this->httpMessage);

        $this->httpMessage->shouldReceive('getBody')->once()->andReturn("Status=0\r\nMessageIDs=151103141334228\r\nType=Fake\r\n");

        $this->client->addToRequest(new JetSMSMessage('content', 'number'));
        $apiResponse = $this->client->sendRequest();
        $this->assertTrue($apiResponse->isSuccess());
        $this->assertEquals(['151103141334228'], $apiResponse->messageReportIdentifiers());
    }

    /** @test */
    public function it_should_return_empty_array_if_no_message_ids_present()
    {
        $this->httpClient->shouldReceive('request')
                         ->once()
                         ->andReturn($this->httpMessage);

        $this->httpMessage->shouldReceive('getBody')->once()->andReturn("Status=0\r\nType=Fake\r\n");

        $this->client->addToRequest(new JetSMSMessage('content', 'number'));
        $apiResponse = $this->client->sendRequest();
        $this->assertTrue($apiResponse->isSuccess());
        $this->assertEquals([], $apiResponse->messageReportIdentifiers());
    }

    /** @test */
    public function it_should_not_boot_without_an_endpoint()
    {
        $this->setExpectedException(CouldNotBootClient::class);

        new JetSMSClient($this->httpClient, '', 'bar', 'baz');
        $this->clientWithNoUsername = new JetSMSClient($this->httpClient, 'foo', '', 'baz');
        $this->clientWithNoPassword = new JetSMSClient($this->httpClient, 'foo', 'bar', '');
    }

    /** @test */
    public function it_should_not_boot_without_a_username()
    {
        $this->setExpectedException(CouldNotBootClient::class);

        new JetSMSClient($this->httpClient, 'foo', '', 'baz');
    }

    /** @test */
    public function it_should_not_boot_without_a_password()
    {
        $this->setExpectedException(CouldNotBootClient::class);

        new JetSMSClient($this->httpClient, 'foo', 'bar', '');
    }

    /** @test */
    public function it_should_handle_api_fail_with_known_errors()
    {
        $this->httpClient->shouldReceive('request')
                         ->once()
                         ->andReturn($this->httpMessage);

        $this->httpMessage->shouldReceive('getBody')->once()->andReturn("Status=-1\r\nType=Fake\r\n");
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->client->addToRequest(new JetSMSMessage('content', 'number'));
        $response = $this->client->sendRequest();
        $this->assertEquals(-1, $response->errorCode());
        $this->assertEquals('The specified SMS outbox name is invalid.', $response->errorMessage());
    }

    /** @test */
    public function it_should_handle_api_fail_with_unknown_errors()
    {
        $this->httpClient->shouldReceive('request')
                         ->once()
                         ->andReturn($this->httpMessage);

        $this->httpMessage->shouldReceive('getBody')->once()->andReturn("Status=-100\r\nType=Fake\r\n");
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->client->addToRequest(new JetSMSMessage('content', 'number'));
        $response = $this->client->sendRequest();
        $this->assertEquals(-1, $response->errorCode());
    }

    /** @test */
    public function it_should_not_send_request_when_no_originator_is_provided()
    {
        $this->setExpectedException(CouldNotSendNotification::class);

        $this->clientWithNoPredefinedOriginator->addToRequest(new JetSMSMessage('content', 'number'));
        $this->clientWithNoPredefinedOriginator->sendRequest();
    }
}
