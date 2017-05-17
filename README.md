# Lox24 Push Notification Channel for Laravel 5.3

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/lox24.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/lox24)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/lox24/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/lox24)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/36d2ff4a-89b7-445d-8d9d-bfab0bc91e20.svg?style=flat-square)](https://insight.sensiolabs.com/projects/36d2ff4a-89b7-445d-8d9d-bfab0bc91e20)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/lox24.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/lox24)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/lox24/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/lox24/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/lox24.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/lox24)

This package makes it easy to send notifications using [Lox24](https://www.lox24.eu) with Laravel 5.3.

## Contents

- [Installation](#installation)
	- [Setting up the Lox24 service](#setting-up-the-Lox24-service)
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

``` bash
composer require laravel-notification-channels/lox24
```

### Setting up the Lox24 service

Add your Lox24 Account ID and your Lox24 password to your broadcasting config:

```php
// config/broadcasting.php

    'connections' =>
        'lox24' => [
            'accountId' => env('LOX24_ACCOUNT_ID'),
            'password' => env('LOX24_PASSWORD'),
            'from' => env('LOX24_FROM'), // optional sender name
        ]
```

## Usage

Now you can use the channel in your via() method inside the notification:


```php
use NotificationChannels\Lox24\Lox24Channel;
use NotificationChannels\Lox24\Lox24Message;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [Lox24Channel::class];
    }

    public function toLox24($notifiable)
    {
        return Lox24Message::create()
                    ->setText("Your account was approved!")
                    ->setFrom('YOUR SITE');
    }
}
```


In order to let your Notification know to which mobile phone number to send the SMS add  `routeNotificationForLox24` method to your Notifiable model.

You must return phone number.

```php
public function routeNotificationForLox24()
{
    return $this->mobile_phone; // For examle 0049123456789
}
```

Do not forget to add the Service Provider:
```php

    NotificationChannels\Lox24\Lox24ServiceProvider::class

```


### Available methods

- `setText('hello you!')`: Accepts a string value for the SMS Text.
- `setTo('0049123456789')`: Accepts a string value with the receiver phone number.
- `setFrom('sendername')`: Accepts a string value (max 11 characters) with a sender name which will be displayed on receivers phone as sender
- `testOnly()`: Can be used for testing, no SMS will be sent
- `sendAt(\DateTime $time)`: Can be used to schedule sending

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email gotre@teraone.de instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Stefan Gotre](https://github.com/sgotre)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
