# Exponent push notifications channel for Laravel 5.3

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alymosul/laravel-exponent-push-notifications.svg?style=flat-square)](https://packagist.org/packages/alymosul/laravel-exponent-push-notifications)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/Alymosul/laravel-exponent-push-notifications.svg?branch=master)](https://travis-ci.org/Alymosul/laravel-exponent-push-notifications)
[![StyleCI](https://styleci.io/repos/96645200/shield?branch=master)](https://styleci.io/repos/96645200)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/afe0ba9a-e35c-4759-a06f-14a081cf452c/big.png)](https://insight.sensiolabs.com/projects/afe0ba9a-e35c-4759-a06f-14a081cf452c)
[![Quality Score](https://img.shields.io/scrutinizer/g/alymosul/laravel-exponent-push-notifications.svg?style=flat-square)](https://scrutinizer-ci.com/g/alymosul/laravel-exponent-push-notifications)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/alymosul/laravel-exponent-push-notifications/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/alymosul/laravel-exponent-push-notifications/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/alymosul/laravel-exponent-push-notifications.svg?style=flat-square)](https://packagist.org/packages/alymosul/laravel-exponent-push-notifications)

## Contents

- [Installation](#installation)
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
composer require alymosul/exponent-push-notifications
```
You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\ExpoPushNotifications\ExpoPushNotificationsServiceProvider::class,
],
```

## Usage

``` php
use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpoPush($notifiable)
    {
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->body("Your {$notifiable->service} account was approved!");
    }
}
```

### Available Message methods

A list of all available options
- `body('')`: Accepts a string value for the body.
- `enableSound()`: Enables the notification sound.
- `disableSound()`: Mutes the notification sound.
- `badge(1)`: Accepts an integer value for the badge.
- `ttl(60)`: Accepts an integer value for the time to live.

### Managing Recipients

This package registers two endpoints that handle the subscription of recipients, the endpoints are defined in src/Http/routes.php file, used by ExpoController and all loaded through the package service provider.

### Routing a message

By default the exponent "interest" messages will be sent to will be defined using the {notifiable}.{id} convention, for example `App.User.1`, however you can change this behaviour by including a `routeNotificationForExpoPushNotifications()` in the notifiable class method that returns the interest name.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email alymosul@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Aly Suleiman](https://github.com/Alymosul)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
