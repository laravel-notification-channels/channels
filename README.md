# Laravel orange(sms) notification channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/orange.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/orange)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/orange/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/orange)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/orange.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/orange)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/orange/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/orange/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/orange.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/orange)

This package makes it easy to send notifications using [orange(sms)](https://developer.orange.com/apis/sms/) with Laravel 5.3.

## Contents

- [Installation](#installation)
	- [Setting up the orange(sms) service](#setting-up-the-orange-service)
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

```
$ composer require laravel-notification-channels/orange
```

Inside `config/app.php`, add the service provider to the providers array, if necessary

```php
Mediumart\Orange\OrangeServiceProvider::class
```

### Setting up the orange service

Open the `config/services.php` and add a key for the `orange>sms` service like this:

    'orange' => [
        'sms' => [
            'client_id' => '<client_id>',
            'client_secret' => '<client_secret>'
        ]
    ]


## Usage

There is two way of setting a notification to run with this channel. 

First, you may simply return the channel class name from the `via` method of any of your notifications:

```php
use NotificationChannels\Orange\OrangeSMSChannel;

/**
 * Get the notification channels.
 *
 * @param  mixed  $notifiable
 * @return array|string
 */
public function via($notifiable)
{
    return [OrangeSMSChannel::class];
}
```

The second method consist on making use of the [mediumart/notifier](https://github.com/mediumart/notifier) library, that will allows you to return a custom hook name(in this case: 'orange') instead of the class name from the `via` method.

```php
/**
 * Get the notification channels.
 *
 * @param  mixed  $notifiable
 * @return array|string
 */
public function via($notifiable)
{
    return ['orange'];
}
```

To use the `mediumart/notifier` library, first install the package:

```
$ composer require mediumart/notifier
```

and add the service provider in the `config/app.php` providers array, if necessary:

```php
Mediumart\Notifier\NotifierServiceProvider::class
```

Then you will need to declare a **public** property(array) named `notificationsChannels` inside your `App\Providers\AppServiceProvider.php` in order to register the channel like this:

```php
/**
 * $notificationsChannels.
 * 
 * @var array
 */
public $notificationsChannels = [
    \NotificationChannels\Orange\OrangeSMSChannel::class,
];
```

Next, you need a `toOrange` method in any notification that will use this channel. the method should return an instance of `\NotificationChannels\Orange\OrangeMessage::class`.

```php
use NotificationChannels\Orange\OrangeMessage::class;

/**
 * Get the orange sms representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return OrangeMessage
 */
public function toOrange($notifiable)
{
    return (new OrangeMessage)->to('+237690000000')
                              ->from('+237690000000')
                              ->text('Sample text')     
}
```

### Available Message methods

- `to` (the receiver phone number)
- `from` (the sender phone number)
- `text` (the actual text message)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email <daprod2009@gmail.com> instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Isaac Esso](https://github.com/isaacesso)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
