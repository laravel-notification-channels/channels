<?php

namespace NotificationChannels\ExpoPushNotifications;

use ExponentPhpSDK\Expo;
use ExponentPhpSDK\ExpoRegistrar;
use ExponentPhpSDK\ExpoRepository;
use Illuminate\Support\ServiceProvider;
use ExponentPhpSDK\Repositories\ExpoFileDriver;
use NotificationChannels\ExpoPushNotifications\Repositories\ExpoDatabaseDriver;

class ExpoPushNotificationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/exponent-push-notifications.php' => config_path('exponent-push-notifications.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../config/exponent-push-notifications.php', 'exponent-push-notifications');

        if (! class_exists('CreateExponentPushNotificationInterestsTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../migrations/create_exponent_push_notification_interests_table.php.stub' => database_path("/migrations/{$timestamp}_create_exponent_push_notification_interests_table.php"),
            ], 'migrations');
        }

        $this->app->when(ExpoChannel::class)
            ->needs(Expo::class)
            ->give(function () {
                return new Expo(new ExpoRegistrar($this->getInterestsDriver()));
            });

        //Load routes
        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(ExpoRepository::class, get_class($this->getInterestsDriver()));
    }

    /**
     * @return ExpoRepository
     */
    public function getInterestsDriver()
    {
        $driver = config('exponent-push-notifications.interests.driver');

        switch ($driver) {
            case 'database':
                return new ExpoDatabaseDriver();
                break;
            default:
                return new ExpoFileDriver();
        }
    }
}
