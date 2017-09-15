# TurboSMS notifications channel for Laravel 5.5

This package makes it easy to send notifications using [turbosms.ua](https://turbosms.ua/) with Laravel 5.5.

## Contents

- [Installation](#installation)
    - [Setting up the TurboSMS service](#setting-up-the-turbosms-service)
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

```bash
composer require laravel-notification-channels/turbosms
```

You must install the service provider:
```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\TurboSms\TurboSmsServiceProvider::class,
],
```

### Setting up the TurboSMS service

Initial steps:
* register account at [turbosms.ua/registration.html](https://turbosms.ua/registration.html)
* add sender in [turbosms.ua/sign/add.html](https://turbosms.ua/sign/add.html)
* create login and password for SOAP API at [turbosms.ua/route.html](https://turbosms.ua/route.html)

Add your TurboSMS user, password and sender to your `config/services.php`:

```php
// config/services.php
...
'turbosms' => [
    'login'  => env('TURBOSMS_LOGIN'),
    'password' => env('TURBOSMS_PASSWORD'),
    'sender' => env('TURBOSMS_SENDER'),
],
...
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\TurboSms\TurboSmsMessage;
use NotificationChannels\TurboSms\TurboSmsChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [TurboSmsChannel::class];
    }

    public function toTurboSms($notifiable)
    {
        return (new TurboSmsMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```
In your notifiable model, make sure to include a routeNotificationForTurboSms() method, which return the phone number.

```php
public function routeNotificationForTurboSms()
{
    return $this->phone;
}
```

### Available methods

`from()`: Sets the sender's name or phone number.

`content()`: Sets a content of the notification message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email aia@auge.in.ua instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits


- [Andrey Anufriev](https://github.com/loobooger)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
