# Intercom notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ftw-soft/intercom-notification-channel.svg?style=flat-square)](https://packagist.org/packages/ftw-soft/intercom-notification-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://scrutinizer-ci.com/g/ftw-soft/intercom-notification-channel/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ftw-soft/intercom-notification-channel/build-status/master)
[![StyleCI](https://styleci.io/repos/148610087/shield)](https://styleci.io/repos/148610087)
[![Quality Score](https://img.shields.io/scrutinizer/g/ftw-soft/intercom-notification-channel.svg?style=flat-square)](https://scrutinizer-ci.com/g/ftw-soft/intercom-notification-channel)
[![Code Coverage](https://scrutinizer-ci.com/g/ftw-soft/intercom-notification-channel/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ftw-soft/intercom-notification-channel/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/ftw-soft/intercom-notification-channel.svg?style=flat-square)](https://packagist.org/packages/ftw-soft/intercom-notification-channel)

This package makes it easy to send notifications using [Intercom](https://app.intercom.com) with Laravel 5.6.

## Contents

- [Installation](#installation)
    - [Setting up the Intercom service](#setting-up-the-intercom-service)
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
composer require ftw-soft/intercom-notification-channel
```

### Setting up the Intercom service

Once installed you need to register the service provider with the application. Open up `config/app.php` and find the `providers` key.

``` php
'providers' => [

    \FtwSoft\NotificationChannels\Intercom\IntercomServiceProvider::class,

]
```

Put the followings to your `config/services.php`

``` php
'intercom' => [
    'token' => env('INTERCOM_API_KEY')
]
```

Add your Intercom Token to `.env`

```
INTERCOM_API_KEY=xxx
```


## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use FtwSoft\NotificationChannels\Intercom\Contracts\IntercomNotification;
use FtwSoft\NotificationChannels\Intercom\IntercomChannel;
use FtwSoft\NotificationChannels\Intercom\IntercomMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification implements IntercomNotification
{
    public function via($notifiable)
    {
        return ["intercom"];
    }

    public function toIntercom($notifiable): IntercomMessage
    {
        return IntercomMessage::create("Hey User!")
            ->from(123)
            ->toUserId(321);
    }
}
```


### Available methods

- `body('')`: Accepts a string value for the Intercom message body 
- `email()`: Accepts a string value for the Intercom message type `email` 
- `inapp()`: Accepts a string value for the Intercom message type `inapp` (default)
- `subject('')`: Accepts a string value for the Intercom message body (using with `email` type)
- `plain()`:  Accepts a string value for the Intercom message plain template
- `personal()`: Accepts a string value for the Intercom message personal template
- `from('123')`: Accepts a string value of the admin's id (sender)
- `to(['type' => 'user', 'id' => '321'])`: Accepts an array value for the recipient data
- `toUserId('')`: Accepts a string value for the Intercom message user by id recipient
- `toUserEmail('')`: Accepts a string value for the Intercom message user by email recipient
- `toContactId('')`: Accepts a string value for the Intercom message contact by id recipient

More info about fields read in [Intercom API Reference](https://developers.intercom.com/intercom-api-reference/reference#admin-initiated-conversation) 

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email android991@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Andrey Telesh](https://github.com/ftw-soft)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
