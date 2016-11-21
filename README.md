# ClickSend Laravel Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/clicksend.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/clicksend)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/clicksend/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/clicksend)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/clicksend.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/clicksend)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/clicksend/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/clicksend/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/clicksend.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/clicksend)

This package makes it easy to send notifications using [ClickSend](https://clicksend.com) with Laravel 5.3.

## Contents

- [Installation](#installation)
	- [Setting up the ClickSend service](#setting-up-the-ClickSend-service)
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
composer require laravel-notification-channels/clicksend
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\ClickSend\ClicksendProvider::class,
],
```

### Setting up the ClickSend service

Add your ClickSend Username and API Key to your `config/services.php`:

```php
// config/services.php
...
'clicksend' => [
    'username' => env('CLICKSEND_USERNAME'),
    'api_key' => env('CLICKSEND_API_KEY'),
    'base_uri' => env('CLICKSEND_BASE_URI'), // optional
],
...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\ClickSend\ClicksendChannel;
use NotificationChannels\ClickSend\ClicksendSmsMessage;
use Illuminate\Notifications\Notification;

class OrderPaid extends Notification
{
    public function via($notifiable)
    {
        return [ClicksendChannel::class];
    }

    public function toClicksend($notifiable)
    {
        return (new ClicksendSmsMessage())
            ->content("Thank you! You successfully paid for your Order #123");
    }
}
```

In order to let your Notification know which phone are you sending to, the channel will look for the `sms` attribute of the Notifiable model. If you want to override this behaviour, add the `routeNotificationForClicksend` method to your Notifiable model.

```php
public function routeNotificationForClicksend()
{
    return '+1234567890';
}
```

### Available methods

#### ClicksendSmsMessage

- `content('')`: Accepts a string value for the notification body.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email omar@clicksend.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [:Omar Usman](https://github.com/omarusman)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
