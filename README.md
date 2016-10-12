Here's the latest documentation on Laravel 5.3 Notifications System:

https://laravel.com/docs/master/notifications

# SMSGatewayMe Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/smsgateway-me.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/smsgateway-me)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/smsgateway-me/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/smsgateway-me)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/smsgateway-me.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/smsgateway-me)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/smsgateway-me/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/smsgateway-me/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/smsgateway-me.svg?style=flat-square)](https://packagist.org/packages/frdteknikelektro/smsgateway-me)

This package makes it easy to send notifications using [SMSGatewayMe](https://smsgateway.me) with Laravel 5.3.


## Contents

- [Installation](#installation)
	- [Setting up the SMSGatewayMe service](#setting-up-the-SMSGatewayMe-service)
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
composer require laravel-notification-channels/smsgateway-me
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\SMSGatewayMe\SMSGatewayMeServiceProvider::class,
],
```

### Setting up the SMSGatewayMe service

Sign up on [SMSGatewayMe](https://smsgateway.me). Setting all needed, then add this to your config:

```php
// config/services.php
...
'smsgateway-me' => [
    'email' => env('SMSGATEWAYME_EMAIL', 'email@example.com'),
    'password' => env('SMSGATEWAYME_PASSWORD', 'password'),
    'device_id' => env('SMSGATEWAYME_DEVICE_ID', '00000')
],
...
```

## Usage

You can now use the channel in your `via()` method inside the Notification class.

``` php
use NotificationChannels\SMSGatewayMe\SMSGatewayMeChannel;
use NotificationChannels\SMSGatewayMe\SMSGatewayMeMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [SMSGatewayMeChannel::class];
    }

    public function toSmsGatewayMe($notifiable)
    {
        return (new SMSGatewayMeMessage)->text('Your invoice has been paid');
    }
}
```

### Routing a message

You should add a `routeNotificationForSmsGatewayMe()` method in your notifiable model:

``` php
...
/**
 * Route notifications for the SMSGatewayMe channel.
 *
 * @return int
 */
public function routeNotificationForSmsGatewayMe()
{
    return $this->phone_number;
}
...
```

### Available methods

- `text($text)`: (string) SMS Text.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Before running a test please configure `routeNotificationForSmsGatewayMe()` and `sendDataProvider()` on [`test/Test.php`](test/Test.php)

``` bash
$ composer test
```

This test will send Hello World SMS.

## Security

If you discover any security related issues, please email frdteknikelektro@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Farid Inawan](https://github.com/frdteknikelektro)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
