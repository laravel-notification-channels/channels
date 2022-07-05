Please see [this repo](https://github.com/laravel-notification-channels/channels) for instructions on how to submit a channel proposal.

# A Boilerplate repo for contributions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/arkesel.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/arkesel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/arkesel/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/arkesel)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SymfonyInsight](https://insight.symfony.com/projects/1d3afee3-9999-4c78-8305-f29bfde310dd/small.svg)](https://insight.symfony.com/projects/1d3afee3-9999-4c78-8305-f29bfde310dd)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/arkesel.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/arkesel)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/arkesel/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/arkesel/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/arkesel.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/arkesel)

This package makes it easy to send notifications using [Arkesel](https://arkesel.com/) with Laravel 5.5+, 6.x and 7.x

This is where your description should go. Add a little code example so build can understand real quick how the package can be used. Try and limit it to a paragraph or two.

## Contents

- [A Boilerplate repo for contributions](#a-boilerplate-repo-for-contributions)
  - [Contents](#contents)
  - [Installation](#installation)
    - [Setting up the Arkesel service](#setting-up-the-arkesel-service)
  - [Usage](#usage)
    - [Available Message methods](#available-message-methods)
  - [Changelog](#changelog)
  - [Testing](#testing)
  - [Security](#security)
  - [Contributing](#contributing)
  - [Credits](#credits)
  - [License](#license)

## Installation

Please also include the steps for any third-party service setup that's required for this package.

### Setting up the Arkesel service

First, create [Sign up](https://account.arkesel.com/signup) for an account. You will be taken to your [SMS Dashboard](https://sms.arkesel.com/user/sms-api/info) where you can find the SMS API keys.

Add these to the `.env` file

```env
```

Next, copy this snippet into the `config/services.php` to load the environment variables.

```php
```

This package assumes that your notifiable Model has a `phone_number` field which will be used as the recipient of the notification. To customize this, add the `routeNotificationForArkesel` method which should return the field to be used.

```php
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Route notifications for the Africas Talking channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForArkesel($notification)
    {
        return $this->phone_number;
    }
}
```

## Usage

Some code examples, make it clear how to use the package

### Available Message methods

A list of all available options

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Security

If you discover any security related issues, please email parables95@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Parables Boltnoel](https://github.com/parables)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
