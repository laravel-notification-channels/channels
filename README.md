# ClickSend notifications channel for Laravel
<!--
[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/clicksend.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/clicksend)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/clicksend/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/clicksend)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/clicksend.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/clicksend)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/clicksend/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/clicksend/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/clicksend.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/clicksend)
-->
This package makes it easy to send notification via [SMS using ClickSend API](https://developers.clicksend.com/docs/rest/v3/?php) with Laravel 6.x, 7.x, 8.x & 9.x

## Contents

- [Installation](#installation)
    - [Setting up a ClickSend account](#set-up-a-clicksend-account)
    - [Install the package via composer](#install-the-package-via-composer)
    - [Configuration env](#configuration-env)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

### Set up a ClickSend account

You'll need a ClickSend account. Head over to their [website](https://clicksend.com/) and create or login to your account.

From the dashboard, in the sidebar... `Developers` then `API Credentials` to find your API credentials.

### Install the package via composer

```bash
composer require laravel-notification-channels/clicksend
```
### Configuration env
Add your ClickSend API credentials to your .env file:

```php
CLICK_SEND_USERNAME=test@tester.com
CLICK_SEND_KEY=YOUR-API-KEY-FROM-CLICKSEND
CLICK_SEND_FROM=8885551212
```
OR, use your ClickSend account credentials...
```php
CLICK_SEND_ACCOUNT_USERNAME=test@tester.com
CLICK_SEND_ACCOUNT_PASSWORD=your_clicksend_account_password
CLICK_SEND_FROM=8885551212
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use \NotificationChannels\ClickSend\ClickSendMessage;
use \NotificationChannels\ClickSend\ClickSendChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [ClickSendChannel::class];
    }

    public function toClickSend($notifiable)
    {
        return (new ClickSendMessage)
            ->content("{$notifiable->name}, your account was approved!");
    }
}
```
In your notifiable model, make sure to include a `routeNotificationForClickSend()` method, 
provide the number in [international format](https://help.clicksend.com/article/hpkm4oco32-what-format-does-the-recipient-phone-number-need-to-be-in) 
or ClickSend with automatically format it using your accounts [default country](https://dashboard.clicksend.com/messaging-settings/sms/general).

```php
public function routeNotificationForClickSend()
{
    return $this->phone; // 6142345678
}
```

### Available methods

`from('')`: Accepts your sender id (ClickSend from number)

`content('')`: Accepts a string value for the notification body.

`reference('')`: Accepts a reference string, it's a [custom_string used in ClickSend](https://help.clicksend.com/article/qj7wj57leq-what-is-the-custom-string-used-for) delivery reports.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email webrobert@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Robert Wayne](https://github.com/webrobert)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
