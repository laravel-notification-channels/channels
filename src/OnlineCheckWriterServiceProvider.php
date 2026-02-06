<?php

namespace Zilmoney\OnlineCheckWriter;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Zilmoney\OnlineCheckWriter\Channel\OnlineCheckWriterChannel;

class OnlineCheckWriterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/onlinecheckwriter.php' => config_path('onlinecheckwriter.php'),
        ], 'onlinecheckwriter-config');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/onlinecheckwriter.php',
            'onlinecheckwriter'
        );
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OnlineCheckWriterClient::class, function ($app) {
            return new OnlineCheckWriterClient(
                apiKey: config('onlinecheckwriter.api_key'),
                baseUrl: config('onlinecheckwriter.base_url'),
                timeout: config('onlinecheckwriter.timeout', 30)
            );
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('onlinecheckwriter', function ($app) {
                return new OnlineCheckWriterChannel(
                    $app->make(OnlineCheckWriterClient::class)
                );
            });
        });
    }
}
