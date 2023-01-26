<?php

namespace NotificationChannels\ClickSend;

use Psr\Http\Client\ClientInterface;
use ClickSend\Api\SMSApi as Client;
use ClickSend\Configuration;
use RuntimeException;

class ClickSend
{
    /**
     * The ClickSend configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * The HttpClient instance, if provided.
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Create a new ClickSend instance.
     *
     * @param  array  $config
     * @param ClientInterface|null $client
     *
     * @return void
     */
    public function __construct(array $config = [], ?ClientInterface $client = null)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * Create a new ClickSend instance.
     *
     * @param  array  $config
     * @param ClientInterface|null  $client
     *
     * @return static
     */
    public static function make(array $config, ?ClientInterface $client = null)
    {
        return new static($config, $client);
    }

    /**
     * Create a new ClickSend Client.
     *
     * @return Client
     *
     * @throws \RuntimeException
     */
    public function client() : Client
    {
        [$username, $password] = $this->firstComboOrFail([
            'apiCredentials' => [
                $this->config['api_username'], $this->config['api_key']
            ],
            'accountCredentials' => [
                $this->config['account_username'], $this->config['account_password']
            ]
        ]);

        $credentials = Configuration::getDefaultConfiguration()
            ->setUsername($username)
            ->setPassword($password);

        return new Client($this->client, $credentials);
    }

    private function firstComboOrFail(array $combos)
    {
        $credentials = collect($combos)
            ->first( function($pair) { return ! in_array(null, $pair,true); });

        if(! $credentials) {
            throw new RuntimeException('Please provide your ClickSend API credentials.
            Possible combinations: api_username + api_key OR account_username + account_password.');
        }

        return $credentials;
    }
}
