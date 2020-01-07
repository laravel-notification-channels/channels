<?php

namespace NotificationChannels\Interfax;

use Illuminate\Support\ServiceProvider;
use Interfax\Client;

class InterfaxServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->when(InterfaxChannel::class)
            ->needs(Client::class)
            ->give(function () {
                $options = [
                    'username' => config('services.interfax.username'),
                    'password' => config('services.interfax.password'),
                ];

                if (config('services.interfax.pci')) {
                    $options['base_uri'] = 'https://rest-sl.interfax.net';
                }

                return new Client($options);
            });
    }
}
