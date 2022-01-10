
# BulkGate SMS notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/bulkgate.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/bulkgate)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/bulkgate/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/bulkgate)
[![StyleCI](https://github.styleci.io/repos/431969862/shield?branch=bulkgate)](https://github.styleci.io/repos/431969862?branch=bulkgate)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/bulkgate.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/bulkgate)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/bulkgate/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/bulkgate/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/bulkgate.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/bulkgate)

This package makes it easy to send notifications using [BulkGate](https://bulkgate.com) with Laravel 6.x, 7.x and 8.x.

## Contents

- [Installation](#installation)
    - [Setting up the BulkGate service](#setting-up-the-BulkGate-service)
    - [Advanced configuration](#advanced-configuration)
- [Usage](#usage)
    - [Routing Bulkgate notifications](#routing-bulkgate-notifications)
    - [Available Message methods](#available-message-methods)
    - [Notification](#notification)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation
You can install the package via composer:

``` bash
composer require laravel-notification-channels/bulkgate
```

Or you can manually update composer.json and run `composer update`:
```json
{
    "require": {
        "laravel-notification-channels/bulkgate": "^1.0"
    } 
}
```

### Setting up the BulkGate service

Add your BulkGate App ID and App Token to your .env:

```
BULKGATE_APP_ID=12345 # always required
BULKGATE_APP_TOKEN=ABCD # always required
BULKGATE_SEND_UNICODE=true # optional
BULKGATE_SENDER_ID=12345 # optional
```

([How to get API Access data](https://help.bulkgate.com/docs/en/api-administration.html#how-do-i-get-api-access-data))

### Advanced configuration

Publish config file `bulkgate-notification-channel.php`:
``` bash
php artisan vendor:publish --provider="NotificationChannels\BulkGate\BulkGateServiceProvider"
```

## Usage

### Routing Bulkgate notifications
In order to send notifications to BulkGate, you need to specify recipient phone number.
The channel will automatically add the recipient phone number to the notification message from the `phone_number`
attribute of the notifiable model. If you want to use a different attribute, you can specify it in the `routeNotificationForBulkGate` method.

```php
public function routeNotificationForBulkGate()
{
    return $this->another_phone_number;
}
```
### Notification

Now you can use the channel in your `via()` method inside the notification:
``` php
use Illuminate\Notifications\Notification;
use NotificationChannels\BulkGate\BulkGateChannel;
use NotificationChannels\BulkGate\BulkGateMessage;

class OrderShipped extends Notification
{

    public function via($notifiable): array
    {
        return [BulkGateChannel::class];
    }

    public function toBulkGate($notifiable): BulkGateMessage
    {
        return BulkGateMessage::create('Your order has been shipped!');
        // or
        return (new BulkGateMessage)->text('Your order has been shipped!');
    }
}
```

### Available Message methods

 * `text($text)` - _(string)_ Text message
 * `to($phoneNumber)` - _(string)_ Recipient phone number. This will override the recipient phone number from the notifiable model.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email martin@dontfreakout.eu instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Martin Vlcek](https://github.com/dontfreakout)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
