# Laravel Notification to Bitrix24

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/bitrix-24.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/bitrix-24)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/bitrix-24/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/bitrix-24)
[![StyleCI](https://styleci.io/repos/229822475/shield)](https://styleci.io/repos/229822475)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/bitrix-24.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/bitrix-24)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/bitrix-24/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/bitrix-24/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/bitrix-24.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/bitrix-24)

This package makes it easy to send notifications to bitrix24 with Laravel 5.5+ and 6.0


## Contents

- [Installation](#installation)
	- [Setting up the config](#setting-up-the-config)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Run:
```php
    composer require "laravel-notification-channels/bitrix-24"
```

### Setting up the config

Publish config:

```php
    php artisan vendor:publish --provider="NotificationChannels\Bitrix24\Bitrix24ServiceProvider"
```

To implement notifications, a webhook system based on the Bitrix24 REST API is used (read more about webhooks in the official documentation [Bitrix24](https://dev.1c-bitrix.ru/learning/course/?COURSE_ID=99&LESSON_ID=8581)). This means that you must add a webhook on your Bitrix24 portal yourself and get its token. This token must be entered in the configuration file `config/bitrix24_notice.php`. 
It is also necessary to enter the Bitrix24 user ID in the configuration file, on behalf of which notifications will be sent, and the subdomain of your company in Bitrix24.

> In the configuration file, you must fill in all the fields described above, otherwise the notifications will not work.

## Usage

In your `via()` you can use a channel:

```php
   use NotificationChannels\Bitrix24\Bitrix24Channel;
   use NotificationChannels\Bitrix24\Bitrix24Message;
   use Illuminate\Notifications\Notification;
   
   class BitrixNotice extends Notification
   {
       protected $invoice;
       
       public function __construct($invoice)
       {
           $this->invoice = $invoice;
       }
       
       public function via($notifiable)
       {
           return [Bitrix24Channel::class];
       }
   
       public function toBitrix24($notifiable)
       {
           $data = [
               'invoice' => $this->invoice,
           ];
           
           return (new Bitrix24Message)
                       ->view('notice', $data)
                       ->toUser();
       }
   }
```

The package expects to be given the ID of the chat to send the message to, or the ID of the user being notified.

For example, if the bitrix24 user ID is `56`, you can create a notification for It like this:

```php
    Notification::send(56, new BitrixNotice($invoice));
```

or

```php
    Notification::route('bitrix24', '56')
                ->notify(new BitrixNotice($invoice));
```

If you use your model data for notification, you should add the following function to the notified model, which should return a number - the chat ID or user ID:

```php
    public function routeNotificationForBitrix24(): int
    {
        return $this->bitrix_id;
    }
```

### Available Message methods

`view()` You can use the Blade template as a notification. The method takes the name of the template and an array of data that will be used in the template. When using a template, you should still use the formatting described in the [documentation REST API](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=93&LESSON_ID=7679)

`text()` Simple notification text

`toUser()` By default, notifications are sent to the chat whose ID was passed. If you want to send a message to a user, you must pass the user's ID, and call the 'toUser ()' method on the `new Bitrix24Message () ' object. Thus, this method determines whether the transmitted ID belongs to the chat or to the user.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email zhidkoff@list.ru instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Sergey Zhidkov](https://github.com/adiafora)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
