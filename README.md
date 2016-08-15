# HipChat Notifications Channel for Laravel 5.3 [WIP]

This package makes it easy to send [HipChat notifications](https://www.hipchat.com) with Laravel 5.3.

## Contents

- [Installation](#installation)
	- [Setting up the HipChat Service](#setting-up-the-hipchat-service)
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
composer require laravel-notification-channels/hipchat
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Hipchat\HipchatProvider::class,
];
```
### Setting up the HipChat service

Add your HipChat Account Token and optionally the default room and Hipchat API server's base url to your `config/services.php`:

```php
// config/services.php

    'hipchat' => [
        'token' => env('HIPCHAT_TOKEN'),
        // Default room (optional)
        'room' => 'Notifications',
        // Base URL for Hipchat API server (optional)
        'url' => 'https://api.your.hipchat.server.com',
    ]
```

## Usage

``` php
use NotificationChannels\Hipchat\HipchatChannel;
use NotificationChannels\Hipchat\HipchatMessage;
use Illuminate\Notifications\Notification;

class UserRegistered extends Notification
{
    public function via($notifiable)
    {
        return [HipchatChannel::class];
    }

    public function toHipchat($notifiable)
    {
        return (new HipchatMessage())
            ->room('New Registrations')
            ->sucess()
            ->notify()
            ->html()
            ->content("<strong>A new user has registered!</strong>");
    }
}
```

### Available methods

#### HipchatMessage

- `room()`: Specifies the room (id or name) to send the notification to.
- `from()`: Sets the optional label to be shown in addition to the sender's name.
- `content()`: Sets a content of the notification message.
- `text()`: Sets the content format to plain text.
- `html()`: Sets the content format to html. Allowed HTML tags: a, b, i, strong, em, br, img, pre, code, lists, tables.
- `color()`: Sets the color for the message. Allowed values: yellow, green, red, purple, gray, random.
- `notify()`: Specifies if a message should trigger a user notification in a Hipchat client.
- `info()`: Sets notification level to `info` and color to `gray`.
- `success()`: Sets notification level to `success` and color to `green`.
- `error()`: Sets notification level to `info` and color to `red`.


## Testing
    
``` bash
$ composer test
```

## Security

If you discover any security related issues, please email pmatseykanets@gmail.com instead of using the issue tracker.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Peter Matseykanets](https://github.com/pmatseykanets)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
