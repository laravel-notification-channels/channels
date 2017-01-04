# Chatwork Notifications Channel for Laravel 5.3 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/luisdalmolin/laravel-zenvia-channel.svg?style=flat-square)](https://packagist.org/packages/e2info/chatwork-notifications)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This package makes it easy to send Chatwork messages using [Chatwork API](http://developer.chatwork.com/ja/) with Laravel 5.3.

## Contents

- [Installation](#installation)
- [Configuration](#configuration)
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
composer require e2info/chatwork-notifications
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Chatwork\ChatworkServiceProvider::class,
],
```

## Configuration

Configure your credentials: 

```php
// config/services.php
...
'chatwork' => [
    'api_token' => env('CHATWORK_API_TOKEN'),
],
...
```

## Usage

You can now use the channel in your `via()` method inside the Notification class.

``` php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Chatwork\ChatworkMessage;
use NotificationChannels\Chatwork\ChatworkChannel;

class ChatworkPosted extends Notification
{

    use Queueable;

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        return [ChatworkChannel::class];
    }

    public function toChatwork($notifiable)
    {
        return (new ChatworkMessage())
                        ->message('message');
    }
}

```

or

``` php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Chatwork\ChatworkInformation;
use NotificationChannels\Chatwork\ChatworkChannel;

class ChatworkPosted extends Notification
{

    use Queueable;

    public function __construct()
    {
    }

    public function via($notifiable)
    {
        return [ChatworkChannel::class];
    }

    public function toChatwork($notifiable)
    {
        return (new ChatworkInformation())
                        ->informationTitle('InformationTitle')
                        ->informationMessage('InformationMessage');
    }
}

```


### Routing a message

You can either send the notification by providing with the room_id of the recipient to the roomId($roomId) method like shown in the above example or add a routeNotificationForChatwork() method in your notifiable model:

```php
...
/**
 * Route notifications for the Chatwork channel.
 *
 * @return int
 */
public function routeNotificationForChatwork()
{
    return '99999999'; // Chatwork Room ID
}
...
```

### Available Message methods

#### Message(ChatworkMessage)

- `roomId('roomId')`: (integer|string) Chatwork Room ID.
- `to('accountId')`: (integer|string) .
- `message('message')`: (string) Chat message.

#### Information(ChatworkInformation)

- `roomId('roomId')`: (integer|string) Chatwork Room ID.
- `informationTitle('title')`: (string) Information Box Title.
- `informationMessage('message')`: (string) Information Box Message.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email kaneko@e2info.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [e2kaneko](https://github.com/e2kaneko)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.