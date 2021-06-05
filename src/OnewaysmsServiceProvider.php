<?php

namespace NotificationChannels\Onewaysms;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class OnewaysmsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(OnewaysmsApi::class, static function ($app) {
            return new OnewaysmsApi(
                config('services.onewaysms.user'),
                config('services.onewaysms.pwd'),
                new HttpClient()
            );
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('onewaysms', function ($app) {
                return new OnewaysmsChannel(
                    $app[OnewaysmsApi::class],
                    $this->app['config']['services.onewaysms.sender']
                );
            });
        });
    }
    
}