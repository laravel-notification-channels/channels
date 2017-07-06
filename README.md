Use this repo as a skeleton for your new channel, once you're done please submit a Pull Request on [this repo](https://github.com/laravel-notification-channels/realtime-push-notifications) with all the files.

Here's the latest documentation on Laravel 5.3 Notifications System: 

https://laravel.com/docs/master/notifications

# Realtime Push Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/realtime-push-notifications.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/realtime-push-notifications)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/realtime-push-notifications/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/realtime-push-notifications)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/realtime-push-notifications.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/realtime-push-notifications)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/realtime-push-notifications/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/realtime-push-notifications/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/realtime-push-notifications.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/realtime-push-notifications)

This package makes it easy to send notifications using [Realtime.co](https://realtime.co) with Laravel 5.3.

## Contents

- [Installation](#installation)
	- [Setting up the Realtime.co service](#setting-up-the-Realtime.co-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/realtime-push-notifications
```

### Setting up the Realtime Push service

Add your Realtime credentials to your `config/services.php`:

```php
// config/services.php
'realtimepush' => [
        'applicationKey' => env('[APPLICATION_KEY]]'),
        'privateKey'	=> env('[PRIVATE_KEY]]'),
    ],
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

```php

use NotificationChannels\RealtimePushNotifications\RealtimeChannel;
use NotificationChannels\RealtimePushNotifications\RealtimeMessage;
use NotificationChannels\RealtimePushNotifications\Exceptions\CouldNotSendNotification;

class TestNotification extends Notification
{
    public function toRealtimePushMesssage($notifiable)
    {
        return RealtimeMessage::create()
            ->iosTitle('Realtime Custom Push Notifications')
            ->iosSubtitle('Now with iOS 10 support!')
            ->iosBody('Add multimedia content to your notifications')
            ->sound('default')
            ->badge(1)
            ->iosMutableContent(1)
            ->iosAttachmentUrl('https://framework.realtime.co/blog/img/ios10-video.mp4')
            ->androidMessage('Realtime Custom Push Notifications')
            ->androidPayload(array('foo'=>'bar'));
    }
}
```
In order to let your Notification know which channels to send to, add the `routeNotificationForRealtimePush` method to your Notifiable model.

This method needs to return the channel for notifications.
Do not forget to set the method of targeting users with `sendTo()` if necessary (see below).

```php
public function routeNotificationForRealtimePush()
{
    return 'NOTIFICATIONS_CHANNEL';
}
```


### Available Message methods

- `iosTitle($title)`: Sets iOS Alert title text.
- `iosSubtitle($iosSubtitle)`: Sets iOS Alert subtitle text.
- `iosBody($body)`: Sets iOS Alert body text.
- `sound($sound)`: Sets iOS notification sound.
- `badge($badge)`: Sets iOS icon badge number.
- `iosMutableContent($iosMutableContent)`: Sets iOS mutable content number.
- `iosAttachmentUrl($iosAttachmentUrl)`: Sets iOS mutable content attachment url.
- `androidMessage($androidMessage)`: Sets android message text, the `M` field.
- `iosPayload($iosPayload)`: Sets the message iOS payload. 
- `androidPayload($androidPayload)`: Sets android payload.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```
## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Realtime.co](https://github.com/realtime-framework)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
