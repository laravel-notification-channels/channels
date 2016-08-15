<?php

namespace NotificationChannels\PubNub;

use Illuminate\Support\ServiceProvider;
use Pubnub\Pubnub;

class PubNubServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(PubNubChannel::class)
            ->needs(Pubnub::class)
            ->give(function() {
                $config = config('services.pubnub');

                return new Pubnub(
                    $config['publish_key'],
                    $config['subscribe_key'],
                    $config['secret_key']
                );
            });
    }
}
