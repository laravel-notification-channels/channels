<?php

namespace NotificationChannels\Lox24;

use Illuminate\Support\ServiceProvider;

class Lox24ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $config = config('broadcasting.connections.lox24');
        $this->app->when(Lox24Channel::class)
            ->needs(Lox24::class)
            ->give(function () use ($config){
                return new Lox24(
                    $config['accountId'],
                    $config['password'],
                    $config['from']
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
