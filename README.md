# Redsms notifications channel for Laravel 5.3+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/redsms-ru.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/redsms-ru)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/redsms-ru/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/redsms-ru)
[![StyleCI](https://styleci.io/repos/65589451/shield)](https://styleci.io/repos/65589451)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/aceefe27-ba5a-49d7-9064-bc3abea0abeb.svg?style=flat-square)](https://insight.sensiolabs.com/projects/aceefe27-ba5a-49d7-9064-bc3abea0abeb)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/redsms-ru.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/redsms-ru)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/redsms-ru/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/redsms-ru/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/redsms-ru.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/redsms-ru)

This package makes it easy to send notifications using [redsms.ru](http://redsms.ru) with Laravel 5.3+.

## Contents

- [Installation](#installation)
    - [Setting up the RedsmsRu service](#setting-up-the-RedsmsRu-service)
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
composer require laravel-notification-channels/redsms-ru
```

Then you must install the service provider:
```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\RedsmsRu\RedsmsRuServiceProvider::class,
],
```

### Setting up the RedsmsRu service

Add your RedsmsRu login, API key (hashed password) and default sender name to your `config/services.php`:

```php
// config/services.php
...
'redsmsru' => [
    'login'  => env('REDSMSRU_LOGIN'),
    'secret' => env('REDSMSRU_SECRET'),
    'sender' => env('REDSMSRU_SENDER')
],
...
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\RedsmsRu\RedsmsRuMessage;
use NotificationChannels\RedsmsRu\RedsmsRuChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [RedsmsRuChannel::class];
    }

    public function toRedsmsRu($notifiable)
    {
        return new RedsmsRuMessage("Task #{$notifiable->id} is complete!");
    }
}
```

In your notifiable model, make sure to include a routeNotificationForRedsmsru() method, which return the phone number.

```php
public function routeNotificationForRedsmsru()
{
    return $this->phone;
}
```

### Available methods

`text()`: Set a text of the notification message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email kindly1987@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [projct1](https://github.com/projct1)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.