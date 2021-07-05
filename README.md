# UNIFONIC notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/unifonic.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/unifonic)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/unifonic/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/unifonic)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/unifonic.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/unifonic)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/unifonic/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/unifonic/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/unifonic.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/unifonic)

This package makes it easy to send notifications using [Unifonic Api](https://unifonic.docs.apiary.io/#reference/messages/send) with Laravel 5.5+, 6.x, 7.x and 8.x

## Contents

-   [Requierements](#requirements)
-   [Installation](#installation)
    -   [Setting up the Unifonic service](#setting-up-the-Unifonic-service)
-   [Usage](#usage)
    -   [Available Message methods](#available-message-methods)
-   [Changelog](#changelog)
-   [Testing](#testing)
-   [Security](#security)
-   [Contributing](#contributing)
-   [Credits](#credits)
-   [License](#license)

## Requierements

-   Before start you have to create an account on [Unifonic](https://unifonic.com).

## Installation

1. You can install the package via composer:

```bash
composer require laravel-notification-channels/unifonic
```

### Setting up the Unifonic service

Add your Unifonic AppsId to your `config/services.php`:

```php
// config/services.php
...
'unifonic' => [
    'appsId' => env('UNIFONIC_APPS_ID'),
],
...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

```php
use NotificationChannels\Unifonic\UnifonicChannel;
use NotificationChannels\Unifonic\UnifonicMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [UnifonicChannel::class];
    }

    public function toUnifonic($notifiable)
    {
        return UnifonicMessage::create("Your invoice {$notifiable->order->id} was paid!");
    }
}
```

In order to let your Notification know which phone numer you are targeting, add the `routeNotificationForUnifonic` method to your Notifiable model.

**Important note**: Unifonic requires the recipients phone number to be [E.164](https://developers.omnisend.com/guides/e164-phone-number-formatting)

```php
// app/Models/User.php
public function routeNotificationForUnifonic()
{
    return '+21267064497';
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please email imad@devinweb.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [imad](https://github.com/darbaoui)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
