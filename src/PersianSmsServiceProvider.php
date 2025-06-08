<?php

namespace NotificationChannels\PersianSms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider; // For deferred loading
use GuzzleHttp\Client as HttpClient;
use NotificationChannels\PersianSms\Exceptions\CouldNotSendNotification;
use NotificationChannels\PersianSms\IPPanel\IPPanelChannel; // Updated: Import the class from its new namespace

class PersianSmsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Path to your package's configuration file
        $configPath = __DIR__.'/../config/persian-sms.php';

        // Publish the configuration file
        // php artisan vendor:publish --provider="NotificationChannels\PersianSms\PersianSmsServiceProvider" --tag="persian-sms-config"
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $configPath => config_path('persian-sms.php'),
            ], 'persian-sms-config');
        }

        // Optionally, merge the configuration
        // This allows users to only define the options they want to override.
        $this->mergeConfigFrom($configPath, 'persian-sms');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind the IPPanelChannel into the service container.
        // We'll resolve its dependencies (API key, sender, HttpClient) from the config.
        $this->app->singleton(IPPanelChannel::class, function ($app) { // IPPanelChannel::class now refers to the imported class
            $config = $app['config']['persian-sms.drivers.ippanel'];

            if (empty($config['api_key'])) {
                throw CouldNotSendNotification::apiKeyNotProvided();
            }

            if (empty($config['sender_number'])) {
                // You might want a specific exception for this or use a general one
                throw CouldNotSendNotification::senderNotProvided();
            }

            return new IPPanelChannel( // This will use the imported IPPanelChannel
                new HttpClient($app['config']['persian-sms.guzzle'] ?? []), // Pass Guzzle config
                $config['api_key'],
                $config['sender_number']
                // Optionally, you could pass the API URL from config too if needed:
                // $config['api_url'] ?? 'https://api2.ippanel.com/api/v1'
            );
        });

        // Later, when you add more drivers (Kavenegar, etc.), you might have a factory
        // or a more dynamic way to resolve the active SMS driver channel.
        // For now, we are explicitly binding IPPanelChannel.
        //
        // Example of how you might bind a generic "PersianSmsChannel" that resolves
        // to the configured driver:
        /*
        $this->app->singleton('persian.sms.channel', function ($app) {
            $config = $app['config']['persian-sms'];
            $defaultDriver = $config['default_driver'] ?? 'ippanel'; // e.g., 'ippanel', 'kavenegar'
            $driverConfig = $config['drivers'][$defaultDriver] ?? null;

            if (!$driverConfig) {
                throw new \InvalidArgumentException("SMS driver [{$defaultDriver}] is not configured.");
            }

            switch ($defaultDriver) {
                case 'ippanel':
                    if (empty($driverConfig['api_key']) || empty($driverConfig['sender_number'])) {
                        throw CouldNotSendNotification::apiKeyNotProvided(); // Or more specific
                    }
                    // Ensure you use the correct namespace if you go this route
                    // For example: use NotificationChannels\PersianSms\IPPanel\IPPanelChannel;
                    return new \NotificationChannels\PersianSms\IPPanel\IPPanelChannel(
                        new HttpClient($config['guzzle'] ?? []),
                        $driverConfig['api_key'],
                        $driverConfig['sender_number']
                    );
                // case 'kavenegar':
                //     // return new KavenegarChannel(...);
                //     break;
                default:
                    throw new \InvalidArgumentException("Unsupported SMS driver [{$defaultDriver}].");
            }
        });
        */
    }

    /**
     * Get the services provided by the provider.
     * This is used for deferred loading.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            IPPanelChannel::class, // This will use the imported class's ::class constant
            // 'persian.sms.channel', // If you use the generic channel binding
        ];
    }
}
