# Expo Push Notifications Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/expo.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/expo)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/expo/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/expo)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/expo.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/expo)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/expo/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/expo/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/expo.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/expo)

This package makes it easy to send notifications using [Expo](https://docs.expo.io/push-notifications/overview/) with Laravel 8.x

## Contents

- [Installation](#installation)
	- [Setting up the Expo service](#setting-up-the-Expo-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install this package with Composer:

    composer require laravel-notification-channels/expo

### Setting up the Expo service

If you use an [Expo Access Token](https://docs.expo.io/push-notifications/sending-notifications/#additional-security) please set this in your environment.

    EXPO_ACCESS_TOKEN=mysecrettoken

## Usage
Firstly you will need to define a method to retrieve your Expo push token
````php
class NotifiableModel extends Model {
    // You may pass a single token
    public function routeNotificationForExpo($notification)
    {
        return "ExponentPushToken[xxxxxxxxxxxxxxxxxxxxxx]"
    }
    
    // Or you may return an array of tokens, for example, a user could have multiple devices.
    public function routeNotificationForExpo($notification)
    {
        return $this->installations->pluck('expo_token')->toArray()
    }
}
````


````php
<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Expo\ExpoChannel;
use NotificationChannels\Expo\ExpoMessage;

class NewMessageNotification extends Notification
{
    use Queueable;

    private Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function toExpo($notifiable)
    {
        return ExpoMessage::create()
            ->title("New Message from {$this->message->from}!")
            ->body($this->message->text)
            ->badge(1);
    }
}
````

### Available Message methods



## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

    $ composer test

## Security

If you discover any security related issues, please email nick@npratley.net instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Nick Pratley](https://github.com/nicko170)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
