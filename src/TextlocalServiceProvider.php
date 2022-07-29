<?php

namespace NotificationChannels\Textlocal;

use Illuminate\Support\ServiceProvider;

class TextlocalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.
        //* creating a Textlocal instance when needs by app.
        $this->app->when(TextlocalChannel::class)
            ->needs(Textlocal::class)
            ->give(
                function () {
                    $config = config('textlocal');

                    return new Textlocal(
                        $config['username'],
                        $config['hash'],
                        $config['api_key'],
                        $config['country']
                    );
                }
            );

        $this->publishConfiguration();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $config = __DIR__ . '/../config/textlocal.php';

        $this->mergeConfigFrom($config, 'textlocal');
    }

    public function publishConfiguration()
    {
        $path   =   realpath(__DIR__.'/../config/textlocal.php');
        
        $this->publishes([
            $path => config_path('textlocal.php')
        ], 'textlocal');
    }
}
