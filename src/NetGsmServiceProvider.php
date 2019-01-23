<?php

namespace NotificationChannels\NetGsm;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\NetGsm\Exceptions\InvalidConfiguration;

class NetGsmServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(NetGsmChannel::class)
            ->needs(NetGsmClient::class)
            ->give(function () {
                $config = config('services.netgsm');

                if (is_null($config)) {
                    throw InvalidConfiguration::configurationNotSet();
                }

                return new NetGsmClient(
                    new Client(),
                    $config['user_code'],
                    $config['secret'],
                    $config['msg_header']
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
