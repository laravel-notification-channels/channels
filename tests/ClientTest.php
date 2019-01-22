<?php

namespace NotificationChannels\Pushmix\Test;

use Mockery;
use GuzzleHttp\Psr7\Response;
use Orchestra\Testbench\TestCase;
use Psr\Http\Message\ResponseInterface;
use NotificationChannels\Pushmix\PushmixClient;
use NotificationChannels\Pushmix\Exceptions\InvalidConfiguration;



class ClientTest extends TestCase
{

    protected $client;

    public function setUp()
    {
        parent::setUp();
        $this->client      = Mockery::mock(PushmixClient::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        //setup db config if needed
        $app['config']->set('services.pushmix.key', 'subscription_id');

    }

    public function tearDown()
    {
        Mockery::close();
        parent::tearDown();
    }


    /** @test */
    public function it_can_get_key()
    {
      $client = new PushmixClient();
      $client->setKey('SUBSCRIPTION_ID');
      $this->assertSame('SUBSCRIPTION_ID', $client->getKey());

    }
    /***/


    /** @test */
    public function it_throw_invalid_config_exception()
    {
      $this->expectException(InvalidConfiguration::class);
      \Config::set('services.pushmix.key', null);
      $client = new PushmixClient();
      $client->initKey();

    }
    /***/

    /** @test */
    public function it_can_init_key()
    {
      \Config::set('services.pushmix.key', 'it_can_init_key');
      $client = new PushmixClient();
      $client->initKey();

      $this->assertSame('it_can_init_key', $client->getKey());

    }
    /***/

    /** @test */
    public function it_can_set_key()
    {
      $client = new PushmixClient();
      $client->setKey('SUBSCRIPTION_ID');

      $this->assertContains('SUBSCRIPTION_ID', $client->getAdditionalParams());


    }
    /***/

    /** @test */
    public function it_can_set_async()
    {

      $client = new PushmixClient();
      $client->async(false);

      $this->assertSame(false, $client->getRequestAsync());


    }
    /***/

    /** @test */
    public function it_can_set_api_url()
    {

      $client = new PushmixClient();
      $client->setApiUrl('https://www.pushmix.co.uk/api/notify');

      $this->assertSame('https://www.pushmix.co.uk/api/notify', $client->getApiUrl());


    }
    /***/

    /** @test */
    public function it_can_send_notification()
    {

      $client = new PushmixClient();
      $client->setApiUrl('https://www.pushmix.co.uk/api/ping');
      $client->setKey('SUBSCRIPTION_ID');
      $response = $client->sendNotification([
        'to'          => 'all',
        'title'       => 'Hello',
        'body'        => 'Welcome to Pushmix!',
        'default_url' => 'https://wwww.pushmix.co.uk/docs'
      ]);

      $this->assertSame(200, $response->getStatusCode());
    }
    /***/

    /** @test */
    public function it_can_send_notification_async()
    {

      $client = new PushmixClient();
      $client->setApiUrl('https://www.pushmix.co.uk/api/ping');
      $client->setKey('SUBSCRIPTION_ID');
      $client->async(true);
      $responseCode = null;
      $response = $client->sendNotification([
        'to'          => 'all',
        'title'       => 'Hello',
        'body'        => 'Welcome to Pushmix!',
        'default_url' => 'https://wwww.pushmix.co.uk/docs'
      ])
      ->then( function( ResponseInterface $res ) use(&$responseCode){
        $responseCode = $res->getStatusCode();
      } );
      $response->wait();

      $this->assertSame(200, $responseCode);
    }
    /***/

}
