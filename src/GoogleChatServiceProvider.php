<?php

namespace NotificationChannels\GoogleChat;

use Illuminate\Support\ServiceProvider;

class GoogleChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // $this->app->when(Channel::class)
        //     ->needs(Pusher::class)
        //     ->give(function () {
        //         $pusherConfig = config('broadcasting.connections.pusher');

        //         return new Pusher(
        //             $pusherConfig['key'],
        //             $pusherConfig['secret'],
        //             $pusherConfig['app_id']
        //         );
        //     });

        $this->publishes([
            realpath(__DIR__.'/../config/google-chat.php') => config_path('google-chat.php'),
        ], 'google-chat-config');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/google-chat.php'), 'google-chat');
    }
}
