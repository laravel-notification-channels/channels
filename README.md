# OneWaySMS Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/onewaysms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/onewaysms)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/onewaysms/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/onewaysms)
[![StyleCI](https://styleci.io/repos/229822475/shield)](https://styleci.io/repos/:style_ci_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/onewaysms.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/onewaysms)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/onewaysms/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/onewaysms/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/onewaysms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/onewaysms)

📲  This package makes it easy to send notifications (SMS) using [OneWaySMS](https://www.onewaysms.com.my/) with Laravel 5.5+, 6.x, 7.x and 8.x

## Contents

- [Installation](#installation)
	- [Setting up the OneWaySMS](#setting-up-the-onewaysms-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
	- [On-Demand Notifications](#on-demand-notifications)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install this package via composer:
``` bash
composer require laravel-notification-channels/onewaysms
```

### Setting up the OneWaySMS service

Add your OneWaySMS API account and settings to your `config/services.php` :

```php
// config/services.php

'onewaysms' => [
    'username' => env('SMS_USERNAME', 'YOUR USERNAME HERE'),
    'password' => env('SMS_PASSWORD', 'YOUR PASSWORD HERE'),
    'endpoint' => env('SMS_ENDPOINT', 'https://gateway.onewaysms.com.my/api.aspx'),
    'sender' => env('SMS_SENDER', 'YOUR SENDER ID')
],

```

## Usage

You can use the channel in your via() method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Onewaysms\OnewaysmsMessage;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ["onewaysms"];
    }

    public function toOnewaysms($notifiable)
    {
        return (new OnewaysmsMessage)->content("Your account has been successfully approved !");
    }
}
```

In your notifiable model, make sure to include a routeNotificationForOnewaysms() method, which returns a phone number or an array of phone numbers.

```php
public function routeNotificationForOnewaysms()
{
    return $this->phone;
}
```
### On-Demand Notifications
Sometimes you may need to send a notification to someone who is not stored as a "user" of your application. Using the Notification::route method, you may specify ad-hoc notification routing information before sending the notification :

```php
Notification::route('onewaysms', '+60123456789')                      
            ->notify(new InvoicePaid($invoice));
```
### Available Message methods

`to()`: Sets the recipient's mobile no.

`from()`: Sets the sender id.

`content()`: Set a content of the notification message. This parameter should be no longer than 459 chars (3 message parts),

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mr.putera@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Zulkifli Mohamed](https://github.com/putera)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
