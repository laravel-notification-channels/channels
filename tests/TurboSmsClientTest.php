<?php

namespace NotificationChannels\TurboSms\Test;

use Mockery;
use NotificationChannels\TurboSms\TurboSmsClient;
use NotificationChannels\TurboSms\Exceptions\CouldNotSendNotification;

class TurboSmsClientTest extends \PHPUnit_Framework_TestCase
{
    /** @var $TurboSmsClient TurboSmsClient */
    private $TurboSmsClient;

    /** @var $httpClient TurboSmsHttp */
    private $httpClient;

    public function setUp()
    {
        parent::setUp();

        $this->httpClient = Mockery::mock(TurboSmsHttp::class);
        $this->TurboSmsClient = new TurboSmsClient($this->httpClient);
    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_creates_a_new_TurboSms_http_client_given_login_details()
    {
        $this->assertInstanceOf(TurboSmsClient::class, $this->TurboSmsClient);
    }

    /** @test */
    public function it_gets_a_list_of_failed_queue_codes()
    {
        $listOfFailedCodes = [
            TurboSmsClient::AUTH_FAILED,
            TurboSmsClient::INVALID_DEST_ADDRESS,
            TurboSmsClient::INVALID_API_ID,
            TurboSmsClient::CANNOT_ROUTE_MESSAGE,
            TurboSmsClient::DEST_MOBILE_BLOCKED,
            TurboSmsClient::DEST_MOBILE_OPTED_OUT,
            TurboSmsClient::MAX_MT_EXCEEDED,
        ];

        $this->assertSame($this->TurboSmsClient->getFailedQueueCodes(), $listOfFailedCodes);
    }

    /** @test */
    public function it_gets_a_list_of_retryable_queue_codes()
    {
        $listOfRetryCodes = [
            TurboSmsClient::NO_CREDIT_LEFT,
            TurboSmsClient::INTERNAL_ERROR,
        ];

        $this->assertSame($this->TurboSmsClient->getRetryQueueCodes(), $listOfRetryCodes);
    }

    /** @test */
    public function it_sends_a_message_to_a_single_number()
    {
        $to = ['27848118111'];
        $message = 'Hi there I am a message';

        $this->httpClient->shouldReceive('sendMessage')
            ->once()
            ->with($to, $message)
            ->andReturn($this->getStubSuccessResponse($to));

        $this->TurboSmsClient->send($to, $message);
    }

    /** @test */
    public function it_sends_a_message_to_multiple_numbers()
    {
        $to = ['27848118111', '1234567890'];

        $message = 'Hi there I am a message to multiple receivers';

        $this->httpClient->shouldReceive('sendMessage')
            ->once()
            ->with($to, $message)
            ->andReturn($this->getStubSuccessResponse($to));

        $this->TurboSmsClient->send($to, $message);
    }

    /** @test */
    public function throws_an_exception_on_failed_response_code()
    {
        $this->setExpectedException(
            CouldNotSendNotification::class,
            "TurboSms responded with an error 'Invalid Destination Address: 105'"
        );

        $to = ['27848118']; // Bad number
        $message = 'Hi there I am a message that is bound to fail';

        $this->httpClient->shouldReceive('sendMessage')
            ->once()
            ->with($to, $message)
            ->andReturn($this->getStubErrorResponse($to));

        $this->TurboSmsClient->send($to, $message);
    }

    /**
     * @param $to
     * @return array
     */
    private function getStubSuccessResponse($to)
    {
        $return[] = (object) [
            'id' => 'c15be99ec802d7d6424c7abd846e3bb8', // Returned message ID example
            'destination' => $to,
            'error' => false,
            'errorCode' => false,
        ];

        return $return;
    }

    /**
     * @param $to
     * @return array
     */
    private function getStubErrorResponse($to)
    {
        $return[] = (object) [
            'id' => false,
            'destination' => $to,
            'error' => 'Invalid Destination Address',
            'errorCode' => TurboSmsClient::INVALID_DEST_ADDRESS,
        ];

        return $return;
    }
}
