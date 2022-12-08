
# WhatsApp notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/whatsapp.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/whatsapp)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/whatsapp/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/whatsapp)
[![StyleCI](https://styleci.io/repos/535096163/shield)](https://styleci.io/repos/535096163)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/whatsapp.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/whatsapp)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/whatsapp/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/whatsapp/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/whatsapp.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/whatsapp)

This package makes it easy to send notifications using [WhatsApp Cloud API](https://developers.facebook.com/docs/whatsapp/cloud-api/) with Laravel.

## Contents

- [Installation](#installation)
	- [Setting up the WhatsApp service](#setting-up-the-WhatsApp-service)
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
```
composer require laravel-notification-channels/whatsapp
```
### Setting up the WhatsApp Cloud API

Create a new Meta application and get your Whatsapp `application token` and `phone number id` following the ["Get Started"](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started?locale=en_US#set-up-developer-assets) guide. Place them inside your `.env` file. To load them, add this to your `config/services.php` file:
```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    // Other third-party config...

    'whatsapp' => [
        'from-phone-number-id' => env('WHATSAPP_FROM_PHONE_NUMBER_ID'),
        'token' => env('WHATSAPP_TOKEN'),
    ],

];
```

## Usage

The Whatsapp API only allows you to start conversations if you send a template message. This means that you will only be able to send template notifications from this package.

Whatsapp forces you to configure your templates before using them. You can learn how to configure your templates by following Meta's official guide on ["How to create templates"](https://developers.facebook.com/docs/whatsapp/cloud-api/guides/send-message-templates).

### WhatsApp templates sections

A template is divided into 4 sections: header, body, footer and buttons. The header, body and buttons accept variables. The footer doesn't accept variables. You can only send variables from this package for the header and body. Support for sending variables for buttons has not yet been added.

### Components

You have available several components that can be used to add context (variables) to your templates. The different components can be created with the component factory:

```php
<?php

use NotificationChannels\WhatsApp\Component;

Component::currency($amount, $code = 'EUR');
Component::dateTime($dateTime, $format = 'Y-m-d H:i:s');
Component::document($link);
Component::image($link);
Component::video($link);
Component::text($text);
```
Components supported by Whatsapp template sections:

 - Header: image, video, document and text (the text accepts currency, datetime and text variables)
 - Body: currency, datetime and text

### Send a notification

To use this package, you need to create a notification class, like `MovieTicketPaid` from the example below, in your Laravel application. Make sure to check out [Laravel's documentation](https://laravel.com/docs/master/notifications) for this process.

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WhatsApp\Component;
use NotificationChannels\WhatsApp\WhatsAppChannel;
use NotificationChannels\WhatsApp\WhatsAppTemplate;

class MovieTicketPaid extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsapp($notifiable)
    {
        return WhatsAppTemplate::create()
            ->name('sample_movie_ticket_confirmation') // Name of your configured template
            ->header(Component::image('https://lumiere-a.akamaihd.net/v1/images/image_c671e2ee.jpeg'))
            ->body(Component::text('Star Wars'))
            ->body(Component::dateTime(new \DateTimeImmutable))
            ->body(Component::text('Star Wars'))
            ->body(Component::text('5'))
            ->to('34676010101');
    }
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email hola@netflie.es instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Álex Albarca](https://github.com/netflie)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
