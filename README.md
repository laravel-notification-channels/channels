# RuStore push notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/ru-store.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/ru-store)

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

[![Build Status](https://img.shields.io/travis/laravel-notification-channels/ru-store/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/ru-store)

[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)

[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/ru-store.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/ru-store)

[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/ru-store/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/ru-store/?branch=master)

[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/ru-store.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/ru-store)

This package makes it easy to send notifications using [RuStore](link to service) with Laravel 10.x.


## Contents

- [Installation](#installation)
- [Setting up the RuStore service](#setting-up-the-RuStore-service)
- [Usage](#usage)
- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation
You can install the package via composer:
```bash
  composer require yakoffka/laravel-notification-channels-ru-store
```

Publish the configuration file:
```bash
  php artisan vendor:publish --provider="NotificationChannels\RuStore\RuStoreServiceProvider"
```
Update your .env file with the values obtained from the [RuStore console](https://console.rustore.ru/waiting)


## Usage
In a class using the Notifiable trait (e.g., the User model), implement a method that returns an array of the notifiable user’s push tokens:
```php
    /**
     * Getting an array of ru-store push tokens of user devices
     *
     * @return array
     */
    public function routeNotificationForRuStore(): array
    {
        return $this->ru_store_tokens;
    }
```

Create a notification class, in the via() method of which specify the RuStoreChannel sending channel and add the toRuStore() method:
```php
<?php

declare(strict_types=1);

namespace App\Notifications\Development;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\RuStore\Resources\MessageAndroid;
use NotificationChannels\RuStore\Resources\MessageAndroidNotification;
use NotificationChannels\RuStore\Resources\MessageNotification;
use NotificationChannels\RuStore\RuStoreChannel;
use NotificationChannels\RuStore\RuStoreMessage;

/**
 * User notification sent via console to check the operation of the RuStore channel
 */
class RuStoreTestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public readonly string $title, public readonly string $body)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param User $notifiable
     * @return array
     */
    public function via(User $notifiable): array
    {
        return [
            RuStoreChannel::class,
        ];
    }

    /**
     * Build the RuStoreMessage to be sent via RuStoreChannel.
     *
     * @param User $notifiable
     * @return RuStoreMessage
     */
    public function toRuStore(User $notifiable): RuStoreMessage
    {
        return (new RuStoreMessage(
            notification: new MessageNotification(
                title: 'Test Push by RuStore',
                body: 'Hello! Test body from RuStoreTestingNotification',
            ),
            android: new MessageAndroid(
                notification: new MessageAndroidNotification(
                    title: 'Android test Push by RuStore',
                    body: 'Hello! Android test body from RuStoreTestingNotification',
                )
            )
        ));
    }
}

```


#### Verifying Notification Delivery
To monitor sent notifications, use the following events:
- The NotificationSent event contains a RuStoreReport instance in its response property: ```$report = $event->response;```
- The NotificationFailed event contains a RuStoreReport instance in its data['report'] property: ```$report = Arr::get($event->data, 'report');```

The RuStoreReport::all() method returns a collection of RuStoreSingleReport instances, where each report corresponds to a device (keyed by its push token).

Example: Handling the NotificationSent Event
```php
    // class SentListener

    /**
     * Handle successfully sent notifications.
     */
    public function handle(NotificationSent $event): void
    {
        match ($event->channel) {
            RuStoreChannel::class => $this->handleRuStoreSuccess($event),
            default => null
        };
    }

    /**
     * Log successfully sent RuStore notifications.
     */
    public function handleRuStoreSuccess(NotificationSent $event): void
    {
        /** @var RuStoreReport $report */
        $report = $event->response;

        $report->all()->each(function (RuStoreSingleReport $singleReport, string $token) use ($report, $event): void {
            /** @var Response $response */
            $response = $singleReport->response();
            Log::channel('notifications')->info('RuStoreSuccess: Notification sent successfully', [
                'user' => $event->notifiable->short_info,
                'token' => $token,
                'message' => $report->getMessage()->toArray(),
                'response_status' => $response->status(),
            ]);
        });
    }

```
NOTE: The NotificationSent event is only triggered if there are successfully sent messages.

Example: Handling the NotificationFailed Event
```php
    // class FailedSendingListener

    public function handle(NotificationFailed $event): void
    {
        match ($event->channel) {
            RuStoreChannel::class => $this->handleRuStoreFailed($event),
            default => null
        };
    }

    /**
     * Handle failed RuStore notification deliveries.
     *
     * @param NotificationFailed $event
     * @return void
     */
    private function handleRuStoreFailed(NotificationFailed $event): void
    {
        /** @var RuStoreReport $report */
        $report = Arr::get($event->data, 'report');

        $report->all()->each(function (RuStoreSingleReport $singleReport, string $token) use ($report, $event): void {
            $e = $singleReport->error();
            Log::channel('notifications')->error('RuStoreFailed: Notification delivery error', [
                'user' => $event->notifiable->short_info,
                'token' => $token,
                'message' => $report->getMessage()->toArray(),
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
            ]);
        });
    }

```
NOTE: The NotificationFailed event is only triggered if there is at least one failed delivery.


### Available Message methods

The message supports all the properties described in the [documentation](https://www.rustore.ru/help/sdk/push-notifications/send-push-notifications).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email yagithub@mail.ru instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [yakOffKa](https://github.com/yakoffka)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
