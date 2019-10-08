# Workplace Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/workplace.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/workplace)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/workplace/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/workplace)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/workplace.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/workplace)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/workplace/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/workplace/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/workplace.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/workplace)

This package makes it easy to send notifications using [Workplace](https://work.facebook.com) with Laravel 5.5+ and 6.0

## Contents

- [Installation](#installation)
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
composer require laravel-notification-channels/workplace
```

## Usage

You can now use the channel in your `via()` method inside the Notification class.

``` php
use NotificationChannels\Workplace\WorkplaceChannel;
use NotificationChannels\Workplace\WorkplaceMessage;
use Illuminate\Notifications\Notification;

class WorkplaceNotification extends Notification
{
    public function via($notifiable)
    {
        return [WorkplaceChannel::class];
    }

    public function toWorkplace($notifiable)
    {
        return new WorkplaceMessage('# Laravel Notification Channels are awesome!');
    }
}
```

You also need to add the `routeNotificationForWorkplace()` method in your notifiable model:

``` php
class TestNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForWorkplace()
    {
        return 'https://graph.facebook.com/<groupId>/feed?access_token=<access_token>';
    }
}
```

### Available Message methods

- `content('')`: (string) Notification message, supports markdown.
- `asMarkdown()`: Treats the message content as being Markdown (default)
- `asPlainText()`: Treats the message content as being plain text

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mail@goncaloqueiros.net instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Alexandre Lima](https://github.com/infus0815)
- [Gonçalo Queirós](https://github.com/Ghunti)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
