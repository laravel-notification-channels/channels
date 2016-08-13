<?php

namespace NotificationChannels\:channel_namespace;

use Illuminate\Support\ServiceProvider;

class :service_nameServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.

        /**
         * Here's some example code we use for the pusher package.

        $this->app->when(Channel::class)
            ->needs(Pusher::class)
            ->give(function () {
                $pusherConfig = config('broadcasting.connections.pusher');

                return new Pusher(
                    $pusherConfig['key'],
                    $pusherConfig['secret'],
                    $pusherConfig['app_id']
                );
            });
         */

    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
