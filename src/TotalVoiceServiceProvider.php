<?php

namespace NotificationChannels\TotalVoice;

use Illuminate\Support\ServiceProvider;
use TotalVoice\Client as TotalVoiceService;

class TotalVoiceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(TotalVoiceChannel::class)
            ->needs(TotalVoice::class)
            ->give(function () {
                return new TotalVoice(
                    $this->app->make(TotalVoiceService::class),
                    $this->app->make(TotalVoiceConfig::class)
                );
            });

        $this->app->bind(TotalVoiceService::class, function () {
            $config = $this->app['config']['services.totalvoice'];
            $access_token = array_get($config, 'access_token');

            return new TotalVoiceService($access_token);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(TotalVoiceConfig::class, function () {
            return new TotalVoiceConfig($this->app['config']['services.totalvoice']);
        });
    }
}
