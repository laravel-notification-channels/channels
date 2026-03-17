<?php

namespace NotificationChannels\KwtSms;

use Illuminate\Support\ServiceProvider;
use KwtSMS\KwtSMS;

class KwtSmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/kwtsms.php', 'kwtsms');

        $this->app->singleton(KwtSMS::class, function () {
            return new KwtSMS(
                username: config('kwtsms.username', ''),
                password: config('kwtsms.password', ''),
                sender_id: config('kwtsms.sender', 'KWT-SMS'),
                test_mode: (bool) config('kwtsms.test_mode', false),
            );
        });

        $this->app->singleton(KwtSmsChannel::class, function ($app) {
            return new KwtSmsChannel($app->make(KwtSMS::class));
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/kwtsms.php' => config_path('kwtsms.php'),
            ], 'config');
        }
    }
}
