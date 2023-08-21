<?php

namespace NotificationChannels\LaravelZenviaChannel;

use GuzzleHttp\Client as ZenviaService;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\LaravelZenviaChannel\Exceptions\InvalidConfigException;

class ZenviaServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/zenvia-notification-channel.php', 'zenvia-notification-channel');

        $this->publishes([
            __DIR__.'/../config/zenvia-notification-channel.php' => config_path('zenvia-notification-channel.php'),
        ]);

        $this->app->bind(ZenviaConfig::class, function () {
            return new ZenviaConfig($this->app['config']['zenvia-notification-channel']);
        });

        $this->app->singleton(ZenviaService::class, function (Application $app) {
            /** @var ZenviaConfig $config */
            $config = $app->make(ZenviaConfig::class);

            if ($config->usingAccountPasswordAuth()) {
                return new ZenviaService([
                    'base_uri' => 'https://api-rest.zenvia.com',
                    'headers'  => [
                        'Content-Type'  => 'application/json',
                        'Accept'        => 'application/json',
                        'Authorization' => 'Basic '.base64_encode($config->getAccount().':'.$config->getPassword()),
                    ],
                ]);
            }

            throw InvalidConfigException::missingConfig();
        });

        $this->app->singleton(Zenvia::class, function (Application $app) {
            return new Zenvia(
                $app->make(ZenviaService::class),
                $app->make(ZenviaConfig::class)
            );
        });

        $this->app->singleton(ZenviaChannel::class, function (Application $app) {
            return new ZenviaChannel(
                $app->make(Zenvia::class),
                $app->make(Dispatcher::class)
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            ZenviaConfig::class,
            ZenviaService::class,
            ZenviaChannel::class,
        ];
    }
}
