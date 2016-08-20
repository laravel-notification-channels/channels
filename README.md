# Gammu Notifications Channel for Laravel 5.3 [WIP]

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/gammu.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/gammu)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://travis-ci.org/matriphe/laravel-notification-channel-gammu.svg?branch=master)](https://travis-ci.org/matriphe/laravel-notification-channel-gammu)
[![StyleCI](https://styleci.io/repos/66142304/shield)](https://styleci.io/repos/66142304)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/2c88e16c-8997-4c41-b73d-1f00fddefc10.svg?style=flat-square)](https://insight.sensiolabs.com/projects/2c88e16c-8997-4c41-b73d-1f00fddefc10)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/gammu.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/gammu)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/gammu/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/gammu/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/gammu.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/gammu)

This package makes it easy to send notifications using [Gammu](https://wammu.eu/gammu/) with Laravel 5.3.

## Contents

- [Installation](#installation)
	- [Setting up the Gammu service](#setting-up-the-Gammu-service)
- [Usage](#usage)
    - [Routing a message](#routing-a-message)
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
composer require laravel-notification-channels/gammu
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Gammu\GammuServiceProvider::class,
],
```

### Setting up the Gammu service

Make sure your Gammu has properly configured and able to send SMS by inserting data to `outbox` table.

Then change the database setting to point to Gammu's tables in `config/database.php` by adding this settings below.

```php
// config/database.php
...
'gammu' => [
    'driver' => 'mysql',
    'host' => env('DB_GAMMU_HOST', 'localhost'),
    'port' => env('DB_GAMMU_PORT', '3306'),
    'database' => env('DB_GAMMU_DATABASE', 'forge'),
    'username' => env('DB_GAMMU_USERNAME', 'forge'),
    'password' => env('DB_GAMMU_PASSWORD', ''),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
],
...
```

For sending, add this settings in `config/services.php`.

```php
...
'gammu' => [
    'sender' => env('GAMMU_SENDER', 'sender'),
],
...
``` 

## Usage

You can now use the channel in your `via()` method inside the Notification class.

```php
use NotificationChannels\Gammu\GammuChannel;
use NotificationChannels\Gammu\GammuMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [GammuChannel::class];
    }

    public function toGammu($notifiable)
    {
        return (new GammuMessage())
            ->to($this->invoice->toPhoneNumber)
            ->content("Your {$this->invoice->number} invoice has been paid!");
    }
}
```

If you have multiple senders, you can set the sender by passing `sender` method. If sender is not set, it will use one of sender from `phones` table.

```php
public function toGammu($notifiable)
{
    return (new GammuMessage())
        ->to($this->invoice->toPhoneNumber)
        ->sender($this->invoice->sendingUsingThisProvider)
        ->content("Your {$this->invoice->number} invoice has been paid!");
}
```

### Routing a message

You can either send the notification by providing with the phone number of the recipient to the `to($phoneNumber)` method like shown in the above example or add a `routeNotificationForGammu()` method in your notifiable model.

```php
...
/**
 * Route notifications for the Gammu channel.
 *
 * @return string
 */
public function routeNotificationForGammu()
{
    return $this->phone;
}
...
```

### Available methods

* `to($phoneNumber)` : `(string)` Receiver phone number. Using international phone number (+62XXXXXXXXXX) format is highly suggested.
* `content($content)` : `(string)` SMS content. If content length is more than 160 characters, it will be sent as long SMS automatically.
* `sender($phneId)` : `(string)` Phone sender ID set in Gammu's phone table.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please email halo@matriphe.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Muhammad Zamroni](https://github.com/matriphe)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
