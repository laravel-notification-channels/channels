# M-Stat.gr notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/mstatgr.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/mstatgr)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/mstatgr/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/mstatgr)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/mstatgr.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/mstatgr)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/mstatgr/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/mstatgr/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/mstatgr.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/mstatgr)

This package makes it easy to send notifications using [MstatGr](https://m-stat.gr/) with Laravel 9.x.

This is where your description should go. Add a little code example so build can understand real quick how the package can be used. Try and limit it to a paragraph or two.



## Contents

- [Installation](#installation)
	- [Setting up the MstatGr service](#setting-up-the-MstatGr-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install via composer

```bash
composer require laravel-notification-channels/mstatgr
```

### Setting up the MstatGr service

Add in the `services.php` configuration file the following lines

```php
    'mstat' => [
        'auth_key' => env('MSTAT_AUTH_KEY', ''),
        'default_from' => env('MSTAT_FROM', ''),
    ]
```

## Usage

This is a sample Notification class
```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\MstatGr\MstatGrChannel;
use NotificationChannels\MstatGr\MstatGrMessage;

class TestNotification extends Notification
{
    public function via($notifiable): array
    {
        return [MstatGrChannel::class];
    }

    public function toMstatgr($notifiable)
    {
        return (new MstatGrMessage())
            ->content('This is a sample sms.')
            ->to(306901234567)
            ->from('SENDER');
    }
}

```
### Available Message methods

A list of all available options

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email nikolas@chatzidakis.eu instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Nikolas Chatzidakis](https://github.com/nchatzidakis)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

