# Smsapi notifications channel for Laravel 5.3

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mdrost/laravel-notification-channels-smsapi.svg)](https://packagist.org/packages/mdrost/laravel-notification-channels-smsapi)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/mdrost/laravel-notification-channels-smsapi/master.svg)](https://travis-ci.org/mdrost/laravel-notification-channels-smsapi)
[![StyleCI](https://styleci.io/repos/87848457/shield)](https://styleci.io/repos/87848457)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/18cfad29-20b9-46c2-90d8-57576079a58a.svg)](https://insight.sensiolabs.com/projects/18cfad29-20b9-46c2-90d8-57576079a58a)
[![Quality Score](https://img.shields.io/scrutinizer/g/mdrost/laravel-notification-channels-smsapi.svg)](https://scrutinizer-ci.com/g/mdrost/laravel-notification-channels-smsapi)
[![Code Coverage](https://scrutinizer-ci.com/g/mdrost/laravel-notification-channels-smsapi/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/mdrost/laravel-notification-channels-smsapi/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/mdrost/laravel-notification-channels-smsapi.svg)](https://packagist.org/packages/mdrost/laravel-notification-channels-smsapi)
[![PHP 7 ready](http://php7ready.timesplinter.ch/mdrost/laravel-notification-channels-smsapi/badge.svg)](https://travis-ci.org/mdrost/laravel-notification-channels-smsapi)

This package makes it easy to send notifications using [Smsapi](https://www.smsapi.pl/) with Laravel 5.3.

## Contents

- [Installation](#installation)
    - [Setting up the Smsapi service](#setting-up-the-smsapi-service)
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
composer require laravel-notification-channels/smsapi
```

You must install the service provider:
```php
// config/app.php
...
'providers' => [
    ...
    NotificationChannels\Smsapi\SmsapiServiceProvider::class,
],
...
```

You can also publish the config file with:

```bash
php artisan vendor:publish --provider="NotificationChannels\Smsapi\SmsapiServiceProvider"
```

### Setting up the Smsapi service

Log in to your [Smsapi dashboard](https://ssl.smsapi.pl/) and configure your preferred authentication method.
Set your credentials and defaults in `config/smsapi.php`:

```php
'auth' => [
    'method' => 'token',
    // 'method' => 'password',
    'credentials' => [
        'token' => env('SMSAPI_AUTH_TOKEN'),
        // 'username' => env('SMSAPI_AUTH_USERNAME'),
        // 'password' => env('SMSAPI_AUTH_PASSWORD'), // Hashed by MD5
    ],
],
'defaults' => [
    'common' => [
        // 'notify_url' => env('SMSAPI_NOTIFY_URL'),
        // 'partner' => env('SMSAPI_PARTNER'),
        // 'test' => env('SMSAPI_TEST', true),
    ],
    'sms' => [
        // 'from' => env('SMSAPI_FROM'),
        // 'fast' => false,
        // 'flash' => false,
        // 'encoding' => 'utf-8',
        // 'normalize' => false,
        // 'nounicode' => false,
        // 'single' => false,
    ],
    'mms' => [
    ],
    'vms' => [
        // 'from' => env('SMSAPI_FROM'),
        // 'try' => 2,
        // 'interval' => 300,
        // 'tts_lector' => 'Agnieszka',
        // 'skip_gsm' => false,
    ],
],
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Smsapi\SmsapiChannel;
use NotificationChannels\Smsapi\SmsapiSmsMessage;

class FlightFound extends Notification
{
    public function via($notifiable)
    {
        return [SmsapiChannel::class];
    }

    public function toSmsapi($notifiable)
    {
        return (new SmsapiSmsMessage())->content("Buy now your flight!");
    }
}
```

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\Smsapi\SmsapiChannel;
use NotificationChannels\Smsapi\SmsapiMmsMessage;

class AnimalTrespassed extends Notification
{
    public $photoId;

    public function via($notifiable)
    {
        return [SmsapiChannel::class];
    }

    public function toSmsapi($notifiable)
    {
        return (new SmsapiMmsMessage())->subject('Animal!')->smil($this->smil());
    }

    private function smil()
    {
        $url = route('photos', ['id' => $this->photoId]);
        $smil =
            "<smil>" .
                "<head>" .
                    "<layout>" .
                        "<root-layout height='100%' width='100%'/>" .
                        "<region id='Image' width='100%' height='100%' left='0' top='0'/>" .
                    "</layout>" .
                "</head>" .
                "<body><par><img src='{$url}' region='Image' /></par></body>" .
            "</smil>";
        return $smil;
    }
}
```

Add a `routeNotificationForSmsapi` method to your Notifiable model to return the phone number(s):

```php
public function routeNotificationForSmsapi()
{
    return $this->phone_number;
}
```

Or add a `routeNotificationForSmsapiGroup` method to return the contacts group:

```php
public function routeNotificationForSmsapiGroup()
{
    return $this->contacts_group;
}
```

### Available Message methods

#### SmsapiSmsMessage

- `to()`
- `group()`
- `content()`
- `template()`
- `from()`
- `fast()`
- `flash()`
- `encoding()`
- `normalize()`
- `nounicode()`
- `single()`
- `date()`
- `notifyUrl()`
- `partner()`
- `test()`

#### SmsapiMmsMessage

- `to()`
- `group()`
- `subject()`
- `smil()`
- `date()`
- `notifyUrl()`
- `partner()`
- `test()`

#### SmsapiVmsMessage

- `to()`
- `group()`
- `file()`
- `tts()`
- `ttsLector()`
- `from()`
- `try()`
- `interval()`
- `skipGsm()`
- `date()`
- `notifyUrl()`
- `partner()`
- `test()`

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mat.drost@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mateusz Drost](https://github.com/mdrost)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
