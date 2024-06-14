# Spryng Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/spryng.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/spryng)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/spryng/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/spryng)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/spryng.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/spryng)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/spryng/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/spryng/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/spryng.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/spryng)

📲  [Spryng](https://www.spryng.nl/en/) Notifications Channel for Laravel

## Contents

- [Installation](#installation)
	- [Setting up the Spryng service](#setting-up-the-Spryng-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

```bash
composer require laravel-notification-channels/spryng
```

Add the configuration to your `services.php` config file:

```php
'spryng' => [
    'key' => env('SPRYNG_API_KEY'),
]
```

### Setting up the Spryng service

You'll need a Spryng account. Head over to their [website](https://www.spryng.nl/en/) and create or login to your account.

Head to your `Profile` and then `Security` in the sidebar to generate a set of API keys.

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use \NotificationChannels\Spryng\SpryngMessage;
use \NotificationChannels\Spryng\SpryngChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [SpryngChannel::class];
    }

    public function toSpryng($notifiable)
    {
        return (new SpryngMessage)
			->setBody("Task #{$notifiable->id} is complete!")
			->setRecipients($notifiable->phone_number)
			->setOriginator(config('app.name'));
    }
}
```

Make sure your Notifiable model has a `phone_number` attribute, which will be used to send the SMS. Also make sure it's a valid phone number.

### Available Message methods

- `setBody('')`: Accepts a string value for the message body.
- `setRecipients('')`: Accepts a string or array value for the recipient(s) phone number.
- `setOriginator('')`: Accepts a string value for the sender name.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email hello@baspa.dev instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Bas van Dinther](https://github.com/Baspa)
- [Spryng](https://www.spryng.nl/en/)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
