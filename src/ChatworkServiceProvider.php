<?php

namespace NotificationChannels\Chatwork;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class ChatworkServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(ChatworkChannel::class)
                ->needs(Chatwork::class)
                ->give(function () {
                    $config = config('services.chatwork');

                    return new Chatwork(
                            $config['api_token'], new HttpClient()
                    );
                });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        
    }
}
