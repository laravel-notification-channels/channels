[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/netgsm.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/netgsm)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/netgsm/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/netgsm)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/netgsm.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/netgsm)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/netgsm/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/netgsm/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/netgsm.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/netgsm)

This package makes it easy to send notifications using [NetGsm](https://www.netgsm.com.tr/) with Laravel 5.3 - 5.7.


## Contents

- [Installation](#installation)
	- [Setting up the NetGsm service](#setting-up-the-NetGsm-service)
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

``` bash
composer require laravel-notification-channels/netgsm
```

### Setting up the NetGsm service

Add the environment variables to your `config/services.php`:

```php
// config/services.php
...
'netgsm' => [
    'user_code' => env('NETGSM_USER_CODE'),
    'secret' => env('NETGSM_SECRET'),
    'msg_header' => env('NETGSM_HEADER'),
],
...
```

Add your NetGsm User Code, Default header (name or number of sender), and secret (password) to your `.env`:

```php
// .env
...
NETGSM_USER_CODE=
NETGSM_SECRET=
NETGSM_HEADER=
],
...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\NetGsm\NetGsmChannel;
use NotificationChannels\NetGsm\NetGsmMessage;
use Illuminate\Notifications\Notification;

class VpsServerOrdered extends Notification
{
    public function via($notifiable)
    {
        return [NetGsmChannel::class];
    }

    public function toNetGsm($notifiable)
    {
        return (new NetGsmMessage("Your {$notifiable->service} was ordered!"));
    }
}
```

You can add recipients (single value or array)

``` php
return (new NetGsmMessage("Your {$notifiable->service} was ordered!"))->setRecipients($recipients);
```

Additionally you can change header

``` php
return (new NetGsmMessage("Your {$notifiable->service} was ordered!"))->setHeader("COMPANY");
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email drtzack@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Talha Zekeriya Durmuş](https://github.com/zek)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
