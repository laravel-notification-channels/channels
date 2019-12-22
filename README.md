# Vodafone Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/vodafone.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/vodafone)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/vodafone/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/vodafone)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/vodafone.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/vodafone)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/vodafone/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/vodafone/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/vodafone.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/vodafone)

This package makes it easy to send sms notifications using [Vodafone](https://www.vodafone.com.au/messaging/smser) with Laravel 5.5+ and 6.x

Building on Laravel's Notification channel, this package allows you to send SMS notifications via the Vodafone SMS gateway service.

Sending an SMS to a user becomes as simple as using:
``` php
$user->notify(new Notification())
```



## Contents

- [Installation](#installation)
	- [Setting up the Vodafone service](#setting-up-the-Vodafone-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install the package using composer
``` bash
composer require laravel-notification-channels/vodafone
```

Add the configuration to your services.php config file:
``` php
'vodafone' => [
    'username' => env('VODAFONE_USERNAME'),
    'password' => env('VODAFONE_PASSWORD'),
]
```

### Setting up the Vodafone service

Using the Vodafone SMS service requires an account with Vodafone, which can be arranged from this location: https://www.vodafone.com.au/messaging/smser

After being provided with your Vodafone account you will receive an API username and password, these need to be entered into your env or config file.

The Vodafone service has the option to send messages with an alpha tag so instead of appearing from a miscellaneous number they come from a pre-defined name.

## Usage

Within your notification you need to add the Vodafone channel to your via() method:
``` php
use Illuminate\Notifications\Notification;
use NotificationChannels\Vodafone\VodafoneChannel;
use NotificationChannels\Vodafone\VodafoneMessage;

class Invitation extends Notification
{
    public function via($notifiable)
    {
        return [VodafoneChannel::class];
    }

    public function toVodafone($notifiable)
    {
        return (new VodafoneMessage)
            ->content($this->content)
            ->from('My App');
    }
```

In your notifiable model, make sure to include a `routeNotificationForVodafone()` method, which should return a full mobile number including country code.

``` php
public function routeNotificationForVodafone()
{
    return $this->phone;
}
```

### Available Message methods

`content()`: Sets the message content of the SMS

`from()`: Sets the qualified sender of the message

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Simon Woodard](https://github.com/Human018)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
