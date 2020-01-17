# TurboSMS Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gvital3230/turbosms-laravel-notification-channel.svg?style=flat-square)](https://packagist.org/packages/gvital3230/turbosms-laravel-notification-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/gvital3230/turbosms-laravel-notification-channel/master.svg?style=flat-square)](https://travis-ci.org/gvital3230/turbosms-laravel-notification-channel)
[![StyleCI](https://styleci.io/repos/233038111/shield)](https://styleci.io/repos/233038111)
[![Quality Score](https://img.shields.io/scrutinizer/g/gvital3230/turbosms-laravel-notification-channel.svg?style=flat-square)](https://scrutinizer-ci.com/g/gvital3230/turbosms-laravel-notification-channel)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/gvital3230/turbosms-laravel-notification-channel/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/gvital3230/turbosms-laravel-notification-channel/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/gvital3230/turbosms-laravel-notification-channel.svg?style=flat-square)](https://packagist.org/packages/gvital3230/turbosms-laravel-notification-channel)

This package makes it easy to send notifications using [TurboSMS](https://turbosms.ua) with Laravel 5.5+ and 6.0

## Contents

- [Installation](#installation)
	- [Setting up the TurboSMS service](#setting-up-the-TurboSMS-service)
- [Usage](#usage)
	- [ On-Demand Notifications](#on-demand-notifications)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install this package via composer:
``` bash
composer require laravel-notification-channels/smspoh
```

### Setting up the TurboSMS service

Add your TurboSMS sms gate login, password and default sender name to your config/services.php:

```php
// config/services.php
...
    'turbosms' => [
        'wsdl_endpoint' => env('TURBOSMS_WSDLENDPOINT', 'http://turbosms.in.ua/api/wsdl.html'),
        'login' => env('TURBOSMS_LOGIN'),
        'password' => env('TURBOSMS_PASSWORD'),
        'sender' => env('TURBOSMS_SENDER'),
        'debug' => env('TURBOSMS_DEBUG', false) //will log sending attempts and results
        'sandbox_mode' => env('TURBOSMS_SANDBOX_MODE', false) //will not invoke API call
    ],
...
```

## Usage

You can use the channel in your via() method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\TurboSMS\TurboSMSMessage;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ["turbosms"];
    }

    public function toTurboSMS($notifiable)
    {
        return (new TurboSMSMessage("Your account was approved!"));       
    }
}
```

In your notifiable model, make sure to include a routeNotificationForTurboSMS() method, which returns a phone number or an array of phone numbers.

```php
public function routeNotificationForTurboSMS()
{
    return $this->phone;
}
```

### On-Demand Notifications
Sometimes you may need to send a notification to someone who is not stored as a "user" of your application. Using the Notification::route method, you may specify ad-hoc notification routing information before sending the notification:

```php
Notification::route('turbosms', '+380501111111')                      
            ->notify(new AccountApproved());
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email 1c.audit@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Vitalii Goncharov](https://github.com/gvital3230)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
