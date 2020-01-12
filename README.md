# Amazon Simple Notification Service (AWS SNS) notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/aws-sns.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/aws-sns)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/aws-sns/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/aws-sns)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/aws-sns.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/aws-sns)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/aws-sns/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/aws-sns/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/aws-sns.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/aws-sns)

This package makes it easy to send notifications using [AWS SNS](https://aws.amazon.com/pt/sns/) with Laravel 5.5+ and 6.0.

**Note:** Replace ```:style_ci_id``` ```:sensio_labs_id``` with their correct values in [README.md](README.md), [CHANGELOG.md](CHANGELOG.md), [CONTRIBUTING.md](CONTRIBUTING.md), [LICENSE.md](LICENSE.md), [composer.json](composer.json) and other files, then delete this line.


## Contents

- [Installation](#installation)
	- [Setting up the AwsSns service](#setting-up-the-aws-sns-service)
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
composer require laravel-notification-channels/aws-sns
```

Add the service provider (only required on Laravel 5.4 or lower):

```php
// config/app.php
'providers' => [
    // ...
    NotificationChannels\AwsSns\SnsServiceProvider::class,
],
```

### Setting up the AWS SNS service

Add your AWS key ID, secret and default region to your `config/services.php`:

```php
// config/services.php

// ...
'sns' => [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
],
// ...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

```php
use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\AwsSns\SnsMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [SnsChannel::class];
    }

    public function toSns($notifiable)
    {
        return (new SnsMessage())
            ->body("Your {$notifiable->service} account was approved!");
        
        // or

        return SnsMessage::create()
            ->body("Your {$notifiable->service} account was approved!");

        // or

        return SnsMessage::create([
            'body' => "Your {$notifiable->service} account was approved!"
        ]);
    }
}
```

In order to let your Notification know which phone are you sending to, the channel 
will look for the `phone_number` attribute of the Notifiable model. If you 
want to override this behaviour, add the `routeNotificationForSns` 
method to your Notifiable model.

```php
public function routeNotificationForSns()
{
    return '+1234567890';
}
```

### Available SnsMessage methods

- `body('')`: Accepts a string value for the notification body.
- `promotional()`: Sets the SMS attribute as the promotional delivery type (default).
- `transactional()`: Sets the SMS attribute as the transactional delivery type.

More information about the SMS Attributes can be found on the [AWS SNS Docs](https://docs.aws.amazon.com/pt_br/sdk-for-php/v3/developer-guide/sns-examples-sending-sms.html#get-sms-attributes).
It's important to know that the attributes set on the message will override the
default ones configured in your AWS account. 

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email claudson@outlook.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Claudson Martins](https://github.com/claudsonm)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
