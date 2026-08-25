<?php

declare(strict_types=1);

namespace NotificationChannels\RuStore;

use Illuminate\Support\ServiceProvider;

class RuStoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/ru-store.php' => config_path('ru-store.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
    }
}
