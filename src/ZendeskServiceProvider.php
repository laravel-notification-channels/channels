<?php

namespace NotificationChannels\Zendesk;

use Zendesk\API\HttpClient;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Zendesk\Exceptions\InvalidConfiguration;

class ZendeskServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->when(ZendeskChannel::class)
            ->needs(HttpClient::class)
            ->give(function () {
                $config = config('services.zendesk');
                if (! isset($config['subdomin'], $config['username'], $config['token'])) {
                    throw InvalidConfiguration::configurationNotSet();
                }

                $client = new HttpClient($config['subdomin']);
                $client->setAuth('basic', ['username' => $config['username'], 'token' => $config['token']]);

                return $client;
            });
    }
}
