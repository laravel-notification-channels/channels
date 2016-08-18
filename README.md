# OVH SMS driver for Laravel Notifications 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/ovh.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/ovh)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/ovh.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/ovh)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/ovh/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/ovh/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/ovh.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/ovh)

This package provide an OVH SMS driver for Laravel Notifications using OVH.

- [OVH SMS homepage](https://www.ovhtelecom.fr/sms/)
- [laravel-ovh-sms](https://github.com/AkibaTech/laravel-ovh-sms)

Here's the latest documentation on Laravel 5.3 Notifications System: 
https://laravel.com/docs/master/notifications

This package makes it easy to send notifications using [OVH SMS](https://www.ovhtelecom.fr/sms/) with Laravel 5.3.

## Contents

- [Installation](#installation)
	- [Setting up the OVH SMS service](#setting-up-the-ovh-sms-service)
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

```bash
composer require laravel-notification-channels/ovh
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Ovh\OvhServiceProvider::class,
],
```

Then, publish the config:

```bash
php artisan vendor:publish --provider="Akibatech\Ovhsms\ServiceProvider"
```

### Setting up the OVH SMS service

* Subscribe to a plan at OVH (20 free credits).
* [Get your credentials](https://api.ovh.com/createToken/index.cgi?GET=/sms&GET=/sms/*&PUT=/sms/*&DELETE=/sms/*&POST=/sms/*)
* Configure your credentials in **config/laravel-ovh-sms.php** in the **ovh** array.
* Configure your SMS account (given in your OVH welcome email)

```php
// config/laravel-ovh-sms.php
...
[
    'app_key'  => env('OVHSMS_API_KEY'),
    'app_secret' => env('OVHSMS_API_SECRET'),
    'consumer_key' => env('OVHSMS_CONSUMER_KEY'),
    'endpoint' => env('OVHSMS_ENDPOINT'),
    'sms_account' => env('OVHSMS_ACCOUNT'),
],
...
```

## Usage

You can use the channel in your via() method inside the notification:

```php
use Illuminate\Notifications\Notification;
use Akibatech\Ovhsms\Notifications\OvhSmsMessage;
use Akibatech\Ovhsms\Notifications\OvhSmsChannel;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [OvhSmsChannel::class];
    }
    
    public function toOvh($notifiable)
    {
        return (new OvhSmsMessage())->content("Your invoice has been paid!");
    }
}
```

### Available methods

* content(''): Specifies the SMS content.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

There's no test at this moment. **php-ovh-sms library** on what this package depends has tests.

## Security

If you discover any security related issues, please use the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Marceau Casals](https://github.com/AkibaTech)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
