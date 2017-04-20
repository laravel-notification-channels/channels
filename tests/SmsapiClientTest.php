<?php

namespace NotificationChannels\Smsapi\Tests;

use Mockery;
use NotificationChannels\Smsapi\SmsapiClient;
use NotificationChannels\Smsapi\SmsapiSmsMessage;
use SMSApi\Api\Response\StatusResponse;
use SMSApi\Client;
use SMSApi\Proxy\Http\AbstractHttp as Proxy;

class SmsapiClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Proxy
     */
    private $proxy;

    /**
     * @var SmsapiClient
     */
    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->proxy = Mockery::mock(Proxy::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $this->client = new SmsapiClient(
            Client::createFromToken('T_PAAMAYIM_NEKUDOTAYIM'),
            [],
            $this->proxy
        );
    }

    /**
     * @test
     */
    public function send_sms()
    {
        $message = (new SmsapiSmsMessage('Lorem ipsum'))->to('48100200300');
        $this->proxy->shouldReceive('makeRequest')->andReturn([
            'output' =>
                '{'.
                '  "count": 1,'.
                '  "list": ['.
                '    {'.
                '      "id": "1460969715572091219",'.
                '      "points": 0.16,'.
                '      "number": "48100200300",'.
                '      "date_sent": 1460969712,'.
                '      "submitted_number": "48100200300",'.
                '      "status": "QUEUE",'.
                '      "error": null,'.
                '      "idx": null'.
                '    }'.
                '  ]'.
                '}',
            'code' => 200,
            'size' => 1,
        ]);
        $response = $this->client->send($message);
        $this->assertTrue(true);
    }
}
