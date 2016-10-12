<?php

namespace NotificationChannels\SMSGatewayMe;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Notifications\Factory as FactoryContract;
use Illuminate\Contracts\Notifications\Dispatcher as DispatcherContract;

class SMSGatewayMeServiceProvider extends ServiceProvider
{
    /**
     * Boot the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SMSGatewayMeChannel::class, function ($app) {
            return new SMSGatewayMeChannel(new Client(['base_uri' => 'https://smsgateway.me'],
              $this->app['config']['services.smsgateway-me.email'],
              $this->app['config']['services.smsgateway-me.password'],
              $this->app['config']['services.smsgateway-me.device_id'],
            ));
        });

        $this->app->alias(
            SMSGatewayMeChannel::class, DispatcherContract::class
        );

        $this->app->alias(
            SMSGatewayMeChannel::class, FactoryContract::class
        );
    }
}
