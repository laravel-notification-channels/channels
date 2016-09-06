# Asterisk notification channel for Laravel 5.3

This package makes it easy to send notifications using [Asterisk Manager Interface (AMI)](https://wiki.asterisk.org/wiki/display/AST/The+Asterisk+Manager+TCP+IP+API) and [Asterisk Chan Dongle](https://github.com/bg111/asterisk-chan-dongle) with Laravel 5.3.

## Contents

- [Installation](#installation)
    - [Setting up the Asterisk configuration](#setting-up-the-Asterisk-configuration)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install this package via composer:

``` bash
composer require laravel-notification-channels/asterisk
```

Next add the service provider to your `config/app.php`:

```php
...
'providers' => [
    ...
     NotificationChannels\Asterisk\AsteriskServiceProvider::class,
],
...
```



### Setting up the Asterisk service

[See enniel/ami documentation](https://github.com/enniel/ami/blob/master/README.md).

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\Asterisk\AsteriskChannel;
use NotificationChannels\Asterisk\AsteriskMessage;
use Illuminate\Notifications\Notification;

class ExampleNotification extends Notification
{
    public function via($notifiable)
    {
        return [AsteriskChannel::class];
    }

    public function toAsterisk($notifiable)
    {
        return AsteriskMessage::create('message text');
    }
}
```


In order to let your Notification know which phone number you are targeting, add the `routeNotificationForAsterisk` method to your Notifiable model.

**Important note**: Asterisk requires the recipients phone number to be in international format. For instance: 0031612345678

```php
public function routeNotificationForAsterisk()
{
    return '0031612345678';
}
```

### Available message methods

- `content('test')`: Set message text.
- `device('modem1')`: Set device for sending SMS message.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Evgeni Razumov](https://github.com/enniel)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
