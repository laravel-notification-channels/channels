Here's the latest documentation on Laravel 5.3 Notifications System: 

https://laravel.com/docs/master/notifications

# 46Elks notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/46Elks.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/46Elks)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/46Elks/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/46Elks)


[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/46Elks.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/46Elks)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/46Elks/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/46Elks/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/46Elks.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/46Elks)

This package makes it easy to send notifications using [46Elks](https://www.46elks.com) with Laravel 5.3.




46Elks has a whole bunch of phone oriented services. This package takes care of:
* SMS

With more endpoints to come. Feel free to contribute.  



## Contents

- [Installation](#installation)
	- [Setting up the 46Elks service](#setting-up-the-46Elks-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

```
composer require dalnix/46elks
```

### Setting up the 46Elks service

You must add the service provider:

```
// config/app.php
'providers' => [
    ...
    NotificationChannels\FourtySixElks\FourtySixElksServiceProvider::class,
],
```

and add the following to your config/services.php

	'46elks' => [
		'username' => env('46ELKS_USERNAME'),
		'password' => env('46ELKS_PASSWORD'),
	],
	
Also remember to update your .env with correct information
## Usage

To use this channel simply create a notification that has the following content:
```
/**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FourtySixElksChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function to46Elks($notifiable)
    {
        return (new FourtySixElksSMS())
	        ->line('Testsms')
	        ->line('Olle')
	        ->to('+46762216234')
	        ->from('Emil');
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

## Security

If you discover any security related issues, please email emil@dalnix.se instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Emil Österlund](https://github.com/larsemil)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
