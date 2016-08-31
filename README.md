# Cmsms notifications channel for Laravel 5.3

This package makes it easy to send [CM SMS messages](https://dashboard.onlinesmsgateway.com/docs) with Laravel 5.3.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Setting up your Cmsms account](#setting-up-your-Cmsms-account)
- [Usage](#usage)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Requirements

- [Sign up](https://dashboard.onlinesmsgateway.com) for a online sms gateway account
- Find your HTTPS API key in your account settings

## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/cmsms
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Cmsms\CmsmsServiceProvider::class,
],
```

## Setting up your Cmsms account

Add your Cmsms Product Token and default originator (name or number of sender) to your `config/services.php`:

```php
// config/services.php
...
'cmsms' => [
    'product_token' => env('CMSMS_PRODUCT_TOKEN'),
    'originator' => env('CMSMS_ORIGINATOR'),
],
...
```

Notice: The originator can contain a maximum of 11 alfanumeric characters.

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\Cmsms\CmsmsChannel;
use NotificationChannels\Cmsms\CmsmsMessage;
use Illuminate\Notifications\Notification;

class VpsServerOrdered extends Notification
{
    public function via($notifiable)
    {
        return [CmsmsChannel::class];
    }

    public function toCmsms($notifiable)
    {
        return (new CmsmsMessage("Your {$notifiable->service} was ordered!"));
    }
}
```

**Important note**: CMCMS requires the recipients phone number to be in international format. For instance: 0031612345678

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email michel@enflow.nl instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Michel Bardelmeijer](https://github.com/mbardelmeijer)
- [All Contributors](../../contributors)

Special thanks to [Peter Steenbergen](http://petericebear.github.io) for the MessageBird template from where this is mostly based on.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
