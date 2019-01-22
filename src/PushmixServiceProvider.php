<?php

namespace NotificationChannels\Pushmix;

use Illuminate\Support\ServiceProvider;
use NotificationChannels\Pushmix\Exceptions\InvalidConfiguration;

class PushmixServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('pushmix', function ($app) {
            return new PushmixClient();
        });
    }

    /***/

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(PushmixChannel::class)
          ->needs(PushmixClient::class)
          ->give(function () {
              if (is_null(config('services.pushmix.key', null))) {
                  throw InvalidConfiguration::configurationNotSet();
              }

              return new PushmixClient();
          });
    }

    /***/

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['pushmix'];
    }

    /***/
}
