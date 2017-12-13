<?php
/**
 * Created by PhpStorm.
 * User: jarno
 * Date: 12.12.2017
 * Time: 11.04.
 */

namespace NotificationChannels\ZonerSmsGateway\IntegrationTest;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use NotificationChannels\ZonerSmsGateway\ZonerSmsGateway;

class ZonerSmsGatewayTest extends TestCase
{
    /** @var ZonerSmsGateway */
    protected $gateway;
    /** @var string */
    protected $numberTo;

    protected function setUp()
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__);
        $dotenv->load();

        $username = getenv('ZONER_USERNAME');
        $password = getenv('ZONER_PASSWORD');
        $this->numberTo = getenv('ZONER_TEST_RECEIVER');

        $httpClient = new Client();

        $this->gateway = new ZonerSmsGateway($username, $password, $httpClient);
    }

    /**
     * @test
     */
    public function sendsSms()
    {
        $tracking = $this->gateway->sendMessage($this->numberTo, 'zonertest', 'hello zoner');
        $this->assertNotEmpty($tracking);
    }
}
