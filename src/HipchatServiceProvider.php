<?php

namespace NotificationChannels\Hipchat;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class HipchatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(HipchatChannel::class)
            ->needs(Hipchat::class)
            ->give(function () {
                return new Hipchat(
                    new HttpClient,
                    config('services.hipchat.url'),
                    config('services.hipchat.token'),
                    config('services.hipchat.room')
                );
            });
    }
}
