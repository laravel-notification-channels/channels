# SparrowSMS Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/sparrowsms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/sparrowsms)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/sparrowsms/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/sparrowsms)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/sparrowsms.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/sparrowsms)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/sparrowsms/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/sparrowsms/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/sparrowsms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/sparrowsms)

This package makes it easy to send notifications using [SparrowSMS](https://sparrowsms.com/) with Laravel 7.0, 8.0


## Contents

- [Installation](#installation)
	- [Setting up the SparrowSMS service](#setting-up-the-SparrowSMS-service)
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
composer require laravel-notification-channels/sparrowsms
```

### Setting up the SparrowSMS service

Add your SparrowSMS api endpoint, token and from to your .env file or config/services.php:

```php
// config/services.php
...
    'sparrowsms' => [
        'endpoint' => env('SPARROW_SMS_ENDPOINT'),
        'token' => env('SPARROW_SMS_TOKEN'),
        'from' => env('SPARROW_SMS_FROM'),
    ],
...
```

## Usage

You can use the channel in your via() method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\SparrowSMS\SparrowSMSMessage;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ["sparrowsms"];
    }

    public function toSparrowSMS($notifiable)
    {
        return (new SparrowSMSMessage("Your account was approved!"));       
    }
}
```

In your notifiable model, make sure to include a routeNotificationForSparrowSMS() method, which returns a phone number or an array of phone numbers.

```php
public function routeNotificationForTurboSMS()
{
    return $this->phone;
}
```

### On-Demand Notifications
Sometimes you may need to send a notification to someone who is not stored as a "user" of your application. Using the Notification::route method, you may specify ad-hoc notification routing information before sending the notification:

```php
Notification::route('sparrow', '+9779841100000')                      
            ->notify(new AccountApproved());
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email shankhadev123@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Padam Shankhadev](https://github.com/shankhadevpadam)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
