# SmsCentre notifications channel for Laravel 5.3 [WIP]

This package makes it easy to send notifications using [SmsCentre](smscentre.com) (aka СМС–Центр) with Laravel 5.3.

## Contents

- [Installation](#installation)
    - [Setting up the SmsCentre service](#setting-up-the-SmsCentre-service)
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
composer require laravel-notification-channels/smscentre
```

You must install the service provider:
```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\SmsCentre\SmsCentreServiceProvider::class,
];
```

### Setting up the SmsCentre service

Add your SmsCentre login, secret key (hashed password) and default sender name  to your `config/services.php`:

```php
// config/services.php

'smscentre' => [
    'login'  => env('SMSCENTRE_LOGIN'),
    'secret' => env('SMSCENTRE_SECRET'),
    'sender' => 'John_Doe'
]
```

## Usage

You can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\SmsCentre\SmsCentreMessage;
use NotificationChannels\SmsCentre\SmsCentreChannel;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [SmsCentreChannel::class];
    }

    public function toSmsCentre($notifiable)
    {
        return (new SmsCentreMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

### Available methods

TODO

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [JhaoDa](https://github.com/jhaoda)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
