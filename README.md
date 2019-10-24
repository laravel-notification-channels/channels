# MoceanAPI notifications channel for Laravel 5.5+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/moceanapi.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/moceanapi)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/moceanapi/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/moceanapi)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/moceanapi.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/moceanapi)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/moceanapi/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/moceanapi/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/moceanapi.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/moceanapi)

This package makes it easy to send notifications using [MoceanAPI](https://moceanapi.com/docs/) with Laravel 5.5+ and 6.0

## Contents

- [Installation](#installation)
	- [Setting up the MoceanAPI](#setting-up-the-moceanapi)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

To install the library, run this command in terminal:
```bash
composer require laravel-notification-channels/moceanapi
```

### Setting up the MoceanAPI service

You must publish the config file as this will use [Laravel Mocean](https://github.com/MoceanAPI/laravel-mocean) as a package.

```bash
php artisan vendor:publish --provider="Mocean\Laravel\MoceanServiceProvider"
```

Setup ur configurations

```php
// config/mocean.php
...
'MOCEAN_API_KEY' => 'YOUR MOCEAN_API_KEY',
'MOCEAN_API_SECRET' => 'YOUR MOCEAN_API_SECRET',
...
```

## Usage

Create a notification class, refer [laravel official docs](https://laravel.com/docs/notifications#creating-notifications)

```php
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\MoceanApi\MoceanApiChannel;
use NotificationChannels\MoceanApi\MoceanApiSmsMessage;

class InvoicePaid extends Notification
{
    use Queueable;
    
    public function via($notifiable)
    {
        return [MoceanApiChannel::class];
    }
    
    public function toMoceanapi($notifiable)
    {
        //return the text message u want to send here
        return 'You have received an invoice';
        
        //you can also return an array for custom options, refer moceanapi docs
        return [
            'mocean-text' => 'You have received an invoice',
            'mocean-dlr-url' => 'http://test.com'
        ];

        //you can also return a MoceanApiSmsMessage instance
        return (new MoceanApiSmsMessage())
            ->setText('You have received an invoice');
    }
}
```

to specify which attribute should be used to be a notifiable entity, create method `routeNotificationForMoceanapi`

```php

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    public function routeNotificationForMoceanapi($notification)
    {
        //make sure user model has this attribute, else the notification will not be sent
        return $this->phone;
    }
}
```

send the notification to a user

```php
$user->notify(new InvoicePaid());
```

you can also send the notification to a custom phone number without using user model

```php
use Notification;

Notification:route('moceanapi', '60123456789')
    ->notify(new InvoicePaid());
```

### Available Message methods

#### MoceanApiSmsMessage

- `setFrom('')`: Set the SMS Sender ID
- `setTo('')`: Set the phone number of the receiver
- `setText('')`: Set the contents of the message

for more info, please visit [MoceanAPI docs](https://moceanapi.com/docs/#send-sms)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email lkloon123@hotmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [NeoSon](https://github.com/lkloon123)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
