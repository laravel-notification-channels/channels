<?php

namespace NotificationChannels\ClickSend;

use ClickSend\Api\SMSApi as Client;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class ClickSendServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/clicksend.php', 'clicksend');

        $this->app->singleton(Client::class, function ($app) {
            $config = $app['config']['clicksend'];

            if ($httpClient = $config['http_client'] ?? null) {
                $httpClient = $app->make($httpClient);
            } elseif (! class_exists('GuzzleHttp\Client')) {
                throw new RuntimeException(
                    'The ClickSend client requires a "psr/http-client-implementation" class such as Guzzle.'
                );
            }

            return ClickSend::make($app['config']['clicksend'], $httpClient)->client();
        });

        $this->app->bind(ClickSendChannel::class, function ($app) {
            return new ClickSendChannel(
                $app->make(Client::class),
                $app['config']['clicksend.sms_from']
            );
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('clicksend', function ($app) {
                return $app->make(ClickSendChannel::class);
            });
        });
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/clicksend.php' => $this->app->configPath('clicksend.php'),
            ], 'clicksend');
        }
    }
}
