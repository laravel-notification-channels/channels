<?php

namespace NotificationChannels\ExpoPushNotifications;

use ExponentPhpSDK\Expo;
use ExponentPhpSDK\ExpoRegistrar;
use ExponentPhpSDK\ExpoRepository;
use ExponentPhpSDK\Repositories\ExpoFileDriver;
use Illuminate\Support\ServiceProvider;

class ExpoPushNotificationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(ExpoChannel::class)
            ->needs(Expo::class)
            ->give(function () {
                return new Expo(new ExpoRegistrar(new ExpoFileDriver()));
            });

        //Load routes
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(ExpoRepository::class, ExpoFileDriver::class);
    }
}
