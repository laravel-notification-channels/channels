Please see [this repo](https://github.com/laravel-notification-channels/channels) for instructions on how to submit a channel proposal.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/transmitmessage.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/transmitmessage)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/transmitmessage/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/transmitmessage)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/transmitmessage.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/transmitmessage)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/transmitmessage/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/transmitmessage/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/transmitmessage.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/transmitmessage)

This package makes it easy to send notifications using [TransmitMessage](https://developer.transmitmessage.com) with Laravel 5.5+ and 6.x


## Contents

- [Installation](#installation)
	- [Setting up the TransmitMessage service](#setting-up-the-TransmitMessage-service)
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
composer require laravel-notification-channels/transmitmessage
```
### Setting up the TransmitMessage service

Add the environment variables to your config/services.php:
```
// config/services.php
...
'transmitmessage' => [
    'apiKey' => env('TRANSMITMESSAGE_APIKEY'),
],
...
```
Add your TransmitMessage API Key to your .env:
```
// .env
...
TRANSMITMESSAGE_APIKEY=
],
...
```
## Usage

You can use the channel in your via() method inside the notification:

```
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\TransmitMessage\TransmitMessageChannel;
use NotificationChannels\TransmitMessage\TransmitMessageMessage;

class SmsSend extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TransmitMessageChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toTransmitMessage($notifiable)
    {
        return (new TransmitMessageMessage())
                    ->setMessage('The introduction to the notification.')
                    ->setRecipient('639481234567')
                    ->setSender('SHARKY');
    }
}
```

### Available Message methods

A list of all available options

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```
## Known Issues

if you encounter these error
```
 certificate problem: unable to get local issuer certificate
 ```
for production we recommend to follow these link for fixing these issue

https://ourcodeworld.com/articles/read/211/unirest-for-php-ssl-certificate-problem-unable-to-get-local-issuer-certificate

but if you are in a rush and working only in local enviroment you can just replace the following

1. go to <ProjectDir>\vendor\transmitmessage\php-client-sdk\src\Configuration.php
2. replace ```https://api.transmitmessage.com/v1/``` with ```http://api.transmitmessage.com/v1/```
    
these will fixed the issue temporarily
## Security

If you discover any security related issues, please email chito@burstsms.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Chito Cascante](https://github.com/codechito)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
