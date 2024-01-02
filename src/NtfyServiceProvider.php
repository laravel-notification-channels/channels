<?php

namespace NotificationChannels\Ntfy;

use Illuminate\Support\ServiceProvider;

class NtfyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(NtfyChannel::class)
            ->needs(Ntfy::class)
            ->give(function () {
                $ntfyConfig = config('broadcasting.connections.ntfy');
                return new Ntfy(
                    $ntfyConfig['host'],
                    $ntfyConfig['port'],
                    $ntfyConfig['username'],
                    $ntfyConfig['password'],
                    new \GuzzleHttp\Client()
                );
            });
    }

}
