# SMS77 notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/sms77.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/sms77)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/sms77/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/sms77)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/sms77.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/sms77)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/sms77/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/sms77/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/sms77.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/sms77)

This package makes it easy to send notifications using [SMS77](https://www.sms77.io/) with Laravel.

## Contents

- [Installation](#installation)
	- [Setting up the SMS77 service](#setting-up-the-SMS77-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

This package can be installed via composer:

```composer require laravel-notification-channels/sms77```

### Setting up the SMS77 service

1. Create an account and get the API key [here](https://www.sms77.io/de/)

2. Add the API key to the `services.php` config file:

	```php
	// config/services.php
	...
	'sms77' => [
		'api_key' => env('SMS77_API_KEY', 'YOUR API KEY HERE')
	],
	...
	```

## Usage

You can use this channel by adding `SMS77Channel::class` to the array in the `via()` method of your notification class. You need to add the `toSms77()` method which should return a `new SMS77Message()` object.

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\SMS77\SMS77Channel;
use NotificationChannels\SMS77\SMS77Message;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [SMS77Channel::class];
    }

    public function toSms77() {
        return (new SMS77Message('Hallo!'))
        ->from('Max')
        ->debug();
    }
}
```

### Available Message methods

- `getPayloadValue($key)`: Returns payload value for a given key.
- `content(string $message)`: Sets SMS message text.
- `to(string $number)`: Set recipients number. 
- `from(string $from)`: Set senders name.
- `delay(string $timestamp)`: Delays message to given timestamp.
- `noReload()`: Disables reload lock.
- `debug()`: Enables debug mode.
- `unicode()`: Sets message encoding to unicode.
- `flash()`: Sends SMS as flash message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mail@mxschll.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Maximilian Schöll](https://github.com/mxschll)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
