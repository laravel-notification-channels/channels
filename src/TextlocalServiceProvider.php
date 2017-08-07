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
                    $textlocalConfig = config('services.sms.textlocal');

                    return new Textlocal(
                        $textlocalConfig['username'],
                        $textlocalConfig['hash']
                    );
                }
            );
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
