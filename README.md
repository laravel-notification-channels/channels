# Pr0gramm Laravel Notifications Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/pr0gramm.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/pr0gramm)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/pr0gramm/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/pr0gramm)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/pr0gramm.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/pr0gramm)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/pr0gramm/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/pr0gramm/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/pr0gramm.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/pr0gramm)

This package makes it easy to send notifications to [Pr0gramm](https://pr0gramm.com/) users with Laravel.

## Contents

- [Installation](#installation)
	- [Setting up the Pr0gramm service](#setting-up-the-Pr0gramm-service)
- [Usage](#usage)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

```bash
composer require laravel-notification-channels/pr0gramm
```

Next, you must load the service provider if you don't use auto-discovery:

```php
// config/app.php
'providers' => [
    // ...
    NotificationChannels\Pr0gramm\Pr0grammServiceProvider::class,
],
```

### Setting up the Pr0gramm service

It is recommended to use the credentials of a bot account, as you will not be able to solve the required captcha. You can read more about the Pr0gramm-API and how to get your credentials [here](https://github.com/pr0gramm-com/api-docs/).

Next, you must add your Pr0gramm Credentials in `config/services.php`:

```php
// config/services.php
'pr0gramm' => [
	'username' => env('PR0GRAMM_USERNAME'),
	'password' => env('PR0GRAMM_PASSWORD'),
],
```

## Usage

In every model you wish to be notifiable via Pr0gramm, you must add a `getPr0grammName` method that returns the name of the user on Pr0gramm.

```php
// app/Models/User.php
public function getPr0grammName(): string
{
	return $this->pr0grammName;
}
```

You can now use the channel in your `via()` method inside the notification (You can also use `'pr0gramm'` as channel name)):

```php
use NotificationChannels\Pr0gramm\Pr0grammChannel;

public function via($notifiable)
{
	return [Pr0grammChannel::class];
}
```

Next, you must add a `toPr0gramm` method to your notification containing the message you wish to send to the user:

```php
public function toPr0gramm($notifiable): string
{
	return 'Message from Laravel';
}
```

> **NOTE - RATE LIMIT**: As the rate limit for sending messages is quite low, you will probably run into the `Pr0grammRateLimitReached`-Exception.
>
> You can handle this exception through your try catch block or when using the queue driver by adding a `failed`-method to your notification and release the job again.

That's it, you're ready to go!

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email info@marcelwagner.dev instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Marcel Wagner](https://github.com/Tschucki)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
