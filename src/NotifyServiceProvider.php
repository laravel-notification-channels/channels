<?php

namespace NotificationChannels\Notify;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Notify\Exceptions\InvalidConfiguration;

class NotifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(NotifyChannel::class)
            ->needs(NotifyClient::class)
            ->give(function () {
                $config = config('services.notify');
                if (is_null($config)) {
                    throw InvalidConfiguration::configurationNotSet();
                }
                return new NotifyClient(new Client());
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
