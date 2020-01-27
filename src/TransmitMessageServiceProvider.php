<?php

namespace NotificationChannels\TransmitMessage;

use Illuminate\Support\ServiceProvider;
use TransmitMessageLib\TransmitMessageClient;

class TransmitMessageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(TransmitMessageChannel::class)
            ->needs(TransmitMessageClient::class)
            ->give(function () {
                $config = config('services.transmitmessage');

                return new TransmitMessageClient(
                    $config['apiKey']
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
