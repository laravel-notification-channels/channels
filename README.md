# Gitter.im notifications channel for Laravel 5.3 [WIP]

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/gitter.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/gitter)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/gitter/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/gitter)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/gitter.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/gitter)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/gitter/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/gitter/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/gitter.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/gitter)

This package makes it easy to send notifications using [Gitter.im](//gitter.im) with Laravel 5.3.

## Contents

- [Installation](#installation)
	- [Setting up the Gitter service](#setting-up-the-Gitter-service)
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
composer require laravel-notification-channels/gitter
```

### Setting up the Gitter service

In order to send message to Gitter rooms, you need to obtain [Personal Access Token or application token](https://developer.gitter.im/apps).

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Gitter\GitterMessage;
use NotificationChannels\Gitter\GitterChannel;

class TaskCompleted extends Notification
{
    public function via($notifiable)
    {
        return [GitterChannel::class];
    }

    public function toGitter($notifiable)
    {
        return (new GitterMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

### Available methods

A list of all available options

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email jhaoda@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [JhaoDa](https://github.com/jhaoda)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
