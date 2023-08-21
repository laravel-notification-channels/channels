# Zenvia notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/guiigaspar/laravel-zenvia-channel.svg?style=flat-square)](https://packagist.org/packages/guiigaspar/laravel-zenvia-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/guiigaspar/laravel-zenvia-channel.svg?style=flat-square)](https://packagist.org/packages/guiigaspar/laravel-zenvia-channel)

This package makes it easy to send notifications using [Zenvia](https://zenviasms.docs.apiary.io) with Laravel 7.x, 8.x, 9.x, 10.x

## Contents

- [Installation](#installation)
    - [Configuration](#configuration)
    - [Advanced Configuration](#advanced-configuration)
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
composer require guiigaspar/laravel-zenvia-channel
```

### Configuration

Add your Zenvia Account, Password, and From Name (optional) to your `.env`:

```dotenv
ZENVIA_ACCOUNT=XYZ
ZENVIA_PASSWORD=XYZ
ZENVIA_FROM=XYZ # optional
```

### Advanced Configuration

Run `php artisan vendor:publish --provider="NotificationChannels\LaravelZenviaChannel\ZenviaServiceProvider"`
```
/config/zenvia-notification-channel.php
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\LaravelZenviaChannel\ZenviaChannel;
use NotificationChannels\LaravelZenviaChannel\ZenviaSmsMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [ZenviaChannel::class];
    }

    public function toZenvia($notifiable)
    {
        return (new ZenviaSmsMessage())
            ->content("Your order {$notifiable->orderId} was approved!");
    }
}
```

In order to let your Notification know which phone are you sending/calling to, the channel will look for the `phone_number` attribute of the Notifiable model. If you want to override this behaviour, add the `routeNotificationForZenvia` method to your Notifiable model.

```php
public function routeNotificationForZenvia()
{
    return '+5511912345678';
}
```

### Available Message methods

#### ZenviaSmsMessage

- `id('')`: Accepts a ID to use as the notification identifier.
- `content('')`: Accepts a string value for the notification body.
- `schedule('')`: Accepts a string value for the notification schedule.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email guiigaspar@live.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Guilherme Gaspar](https://github.com/guiigaspar)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
