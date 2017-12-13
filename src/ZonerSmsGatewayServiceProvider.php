<?php

namespace NotificationChannels\ZonerSmsGateway;

use Illuminate\Support\ServiceProvider;

class ZonerSmsGatewayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(ZonerSmsGatewayChannel::class)
            ->needs(ZonerSmsGateway::class)
            ->give(function () {
                $zonerSmsGatewayConfig = config('services.zoner-sms-gateway');

                return new ZonerSmsGateway(
                    $zonerSmsGatewayConfig['username'],
                    $zonerSmsGatewayConfig['password'],
                    $zonerSmsGatewayConfig['sender']
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
