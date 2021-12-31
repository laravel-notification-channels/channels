# WXWork Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/wxwork.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/wxwork)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/wxwork/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/wxwork)
[![StyleCI](https://styleci.io/repos/443232385/shield)](https://styleci.io/repos/443232385)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/wxwork.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/wxwork)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/wxwork/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/wxwork/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/wxwork.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/wxwork)

This package makes it easy to send notifications using [WXWork]([link to service](https://open.work.weixin.qq.com/api/doc/90000/90136/91770)) with Laravel 5.5+, 6.x, 7.x and 8.x

## Contents

- [WXWork Notifications Channel for Laravel](#wxwork-notifications-channel-for-laravel)
	- [Contents](#contents)
	- [Installation](#installation)
		- [Setting up the WXWork service](#setting-up-the-wxwork-service)
	- [Usage](#usage)
		- [Text Notification](#text-notification)
		- [Markdown Notification](#markdown-notification)
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
composer require laravel-notification-channels/wxwork
```

### Setting up the WXWork service

```php
# config/services.php

'wxwork-bot-api' => [
    'token' => env('WXWORK_BOT_TOKEN', 'YOUR BOT TOKEN HERE'),
	'base_uri' => env('WXWORK_BOT_BASE_URI', 'YOUR BASE URI HERE')
],
```

## Usage

You can now use the channel in your `via()` method inside the Notification class.

### Text Notification

```php
use NotificationChannels\WXWork\WXWorkMessage;
use NotificationChannels\WXWork\WXWorkChannel;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [WXWorkChannel::class];
    }

	public function toWXWork($notifiable)
    {
        return WXWorkMessage::create()->content("Hello there!\nYour invoice has been *PAID*")->toText();
    }
}
```

### Markdown Notification

```php
use NotificationChannels\WXWork\WXWorkMessage;
use NotificationChannels\WXWork\WXWorkChannel;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [WXWorkChannel::class];
    }

	public function toWXWork($notifiable)
    {
        return WXWorkMessage::create()->content("Hello there!\nYour invoice has been *PAID*")->toMarkDown();
    }
}
```

### Available Message methods

A list of all available options

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Dong Lei](https://github.com/:author_username)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.