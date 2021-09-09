<?php

namespace NotificationChannels\ExpoPushNotifications;

use ExponentPhpSDK\Expo;
use ExponentPhpSDK\ExpoRegistrar;
use ExponentPhpSDK\ExpoRepository;
use ExponentPhpSDK\Repositories\ExpoFileDriver;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\ExpoPushNotifications\Repositories\ExpoDatabaseDriver;

class ExpoPushNotificationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();

        $repository = $this->getInterestsDriver();

        $this->shouldPublishMigrations($repository);

        $this->app->when(ExpoChannel::class)
            ->needs(Expo::class)
            ->give(function () use ($repository) {
                return new Expo(new ExpoRegistrar($repository));
            });

        $router = $this->app['router'];
        $router->middlewareGroup('expo.middleware', config('exponent-push-notifications')['middleware']);

        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ExpoRepository::class, get_class($this->getInterestsDriver()));
    }

    /**
     * Gets the Expo repository driver based on config.
     *
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

    /**
     * Publishes the configuration files for the package.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $this->publishes([
            __DIR__.'/../config/exponent-push-notifications.php' => config_path('exponent-push-notifications.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../config/exponent-push-notifications.php', 'exponent-push-notifications');
    }

    /**
     * Publishes the migration files needed in the package.
     *
     * @param  ExpoRepository  $repository
     *
     * @return void
     */
    private function shouldPublishMigrations(ExpoRepository $repository)
    {
        if ($repository instanceof ExpoDatabaseDriver && ! class_exists('CreateExponentPushNotificationInterestsTable')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../migrations/create_exponent_push_notification_interests_table.php.stub' => database_path("/migrations/{$timestamp}_create_exponent_push_notification_interests_table.php"),
            ], 'migrations');
        }
    }
}
