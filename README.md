# Pusher API Notifications

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/pusher-api-notifications.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/pusher-api-notifications)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.com/andreshg112/pusher-api-notifications.svg?branch=master)](https://travis-ci.com/andreshg112/pusher-api-notifications)
[![StyleCI](https://styleci.io/repos/175997406/shield)](https://styleci.io/repos/175997406)
[![SensioLabsInsight](https://insight.symfony.com/projects/1b3c70de-4b10-4f3d-8a27-edd150e64193/mini.svg)](https://insight.symfony.com/projects/1b3c70de-4b10-4f3d-8a27-edd150e64193)
[![Quality Score](https://img.shields.io/scrutinizer/g/andreshg112/pusher-api-notifications.svg?style=flat-square)](https://scrutinizer-ci.com/g/andreshg112/pusher-api-notifications)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/andreshg112/pusher-api-notifications/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/andreshg112/pusher-api-notifications/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/pusher-api-notifications.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/pusher-api-notifications)

This package makes it easy to send notifications using [Pusher API Notifications](https://pusher.com/docs/javascript_quick_start) with Laravel 5.3 or greater.

## Contents

-   [Installation](#installation) - [Setting up the Pusher API Notifications service](#setting-up-the-Pusher-API-Notifications-service)
-   [Usage](#usage) - [Available Message methods](#available-message-methods)
-   [Changelog](#changelog)
-   [Testing](#testing)
-   [Security](#security)
-   [Contributing](#contributing)
-   [Credits](#credits)
-   [License](#license)

## Installation

> While this is not accepted in [Laravel Notification Channels](http://laravel-notification-channels.com/) and added to [Packagist](http://packagist.org), you have to add this in your `composer.json` file:

```json
{
    // ...,
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/andreshg112/pusher-api-notifications.git"
        }
    ]
}
```

Require the package:

```bash
$ composer require laravel-notification-channels/pusher-api-notifications
```

### Setting up the Pusher API Notifications service

This package requires [pusher/pusher-http-laravel ^4.2](https://github.com/pusher/pusher-http-laravel), so after installing this, you have to [configure it](https://github.com/pusher/pusher-http-laravel#configuration).

> If your using Laravel ^5.5, don't worry about adding the service provider to your `config/app.php` file because this package uses [Laravel Package Discovery](https://laravel.com/docs/5.8/packages#package-discovery). If don't, you have to add it:

```php
'providers' => [
    // ...,
    NotificationChannels\PusherApiNotifications\PusherApiServiceProvider::class,
],
```

## Usage

> This is a third-party Laravel Notification Package, so you should know how to use Notifications in Laravel before using this. Docs can be found here: https://laravel.com/docs/master/notifications.

In your notification, add the `PusherApiChannel` to the `via()` function:

```php
use NotificationChannels\PusherApiNotifications\PusherApiChannel;

public function via($notifiable)
{
    return [PusherApiChannel::class];
}
```

Then, create a method called `toApiNotification()` in your notification:

```php
use NotificationChannels\PusherApiNotifications\PusherApiMessage;

public function toApiNotification($notifiable)
{
    return (new PusherApiMessage)
        ->channels($channelName)
        ->event($eventName)
        ->data($data)
        ->socketId($socketId)
        ->debug($debug)
        ->alreadyEncoded($alreadyEncoded);

    // or

    return new PusherApiMessage($channelName, $eventName, $data, $socketId, $debug, $alreadyEncoded);
}
```

### Available Message methods

-   `channels($channelName)`: array or string of channel name(s).
-   `event($eventName)`: the name of the event for the Pusher message.
-   `data($data)`: array, string or something that can be corverted to JSON. It's the body of the Pusher message.
-   `socketId($socketId)`: [optional] socketId of Pusher.
-   `debug($debug)`: boolean that tells Pusher if you're debugging.
-   `alreadyEncoded($alreadyEncoded)`: [optional] If the data is already encoded and you don't want Pusher to convert it, set this to true.

These parameters are the same received by [`Pusher::trigger()`](https://github.com/pusher/pusher-http-laravel#examples) method.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please email andreshg112@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [Andrés Herrera García](https://github.com/andreshg112)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
