# AllMySms notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/all-my-sms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/all-my-sms)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/all-my-sms/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/all-my-sms)
[![StyleCI](https://styleci.io/repos/217854455/shield)](https://styleci.io/repos/217854455)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/7269bfe2-e95a-4448-8f07-2664c1f34934.svg?style=flat-square)](https://insight.sensiolabs.com/projects/7269bfe2-e95a-4448-8f07-2664c1f34934)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/all-my-sms.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/all-my-sms)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/all-my-sms/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/all-my-sms/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/all-my-sms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/all-my-sms)

This package makes it easy to send notifications using [AllMySms](https://www.allmysms.com/) with Laravel 5.5+ and 6.0

## Contents

- [Installation](#installation)
	- [Setting up the AllMySms service](#setting-up-the-AllMySms-service)
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
composer require laravel-notification-channels/all-my-sms
```

### Setting up the AllMySms service

Add the following code to you `config/services.php`:

```php
// config/services.php
...
'all_my_sms' => [
    'uri' => env('ALL_MY_SMS_URI', 'https://api.allmysms.com/http/9.0'),
    'login' => env('ALL_MY_SMS_LOGIN'),
    'api_key' => env('ALL_MY_SMS_API_KEY'),
    'format' => env('ALL_MY_SMS_FORMAT', 'json'),
    'sender' => env('ALL_MY_SMS_SENDER'),
    'universal_to' => env('ALL_MY_SMS_UNIVERSAL_TO'),
],
...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\AllMySms\AllMySmsChannel;
use NotificationChannels\AllMySms\AllMySmsMessage;
use Illuminate\Notifications\Notification;

class ProjectCreated extends Notification
{
    public function via($notifiable)
    {
        return [AllMySmsChannel::class]; // or 'all_my_sms'
    }

    public function toAllMySms($notifiable)
    {
        return new AllMySmsMessage('Content');
    }
}
```

In order to let your Notification know which phone number to use, add the `routeNotificationForAllMySms` method to your Notifiable model.

This method needs to return a phone number.

```php
public function routeNotificationForAllMySms(Notification $notification)
{
    return $this->phone_number;
}
```

For testing purpose you may set the `ALL_MY_SMS_UNIVERSAL_TO` environment variable to always send SMS to the configured number.

### Available Message methods

- `content(string $content)`: Accepts a string value for the sms content.
- `sender(string $sender)`: Accepts a string value for the sender name.
- `campaign(string $campaign)`: Accepts a string value for the sms campaign name.
- `sendAt(\DateTime $sendAt)`: Accepts a DateTime for the sms due date.
- `parameters(array $parameters)`: Accepts an array for the sms parameters.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mikael.popowicz@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mikaël Popowicz](https://github.com/mikaelpopowicz)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
