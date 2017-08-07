This package allows to send SMS using Textlocal API using laravel notifications

Here's the latest documentation on Laravel 5.4 Notifications System: 

https://laravel.com/docs/5.4/notifications

# Found any bugs or improvement open an issue or send me a PR

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/textlocal.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/textlocal)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/textlocal/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/textlocal)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/textlocal.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/textlocal)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/textlocal/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/textlocal/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/textlocal.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/textlocal)

This package makes it easy to send notifications using [textlocal](https://www.textlocal.in/) with Laravel 5.3.+



## Contents

- [Installation](#installation)
	- [Setting up the textlocal service](#setting-up-the-textlocal-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Create an account in textlocal then create an API key or hash(password).

`composer require laravel-notification-channels/textlocal`

### Setting up the textlocal service

put the followings and to your config/services
```
'sms' => [
	'textlocal' => [
		'username'  => env('TEXTLOCAL_USERNAME'),
		'hash'      => env('TEXTLOCAL_HASH'),
		'sender'    => env('TEXTLOCAL_SENDER'),
]
```


## Usage
textlocal
implement this method `routeNotificationForSms()` in your notifiable class/model which will return array of mobile numbers
and lastly implement `toSms()` method in the notification class which will return the (string) sms or template that is defined in textlocal account that needs to be send.

### Available Message methods

A list of all available options

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email manash149@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Manash Jyoti Sonowal](https://github.com/msonowal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## TODO
Need to convert to Guzzle Http as a Client in core
add tests
