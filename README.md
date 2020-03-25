# Google Hangouts Chat notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/hangouts-chat.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/hangouts-chat)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/renanwilliam/hangouts-chat/master.svg?style=flat-square)](https://travis-ci.org/renanwilliam/hangouts-chat)
[![StyleCI](https://styleci.io/repos/249778594/shield)](https://styleci.io/repos/249778594)
[![SymfonyInsight](https://insight.symfony.com/projects/09f89605-6bc8-49f7-a007-272754f110d2/mini.svg)](https://insight.symfony.com/projects/09f89605-6bc8-49f7-a007-272754f110d2)
[![Quality Score](https://img.shields.io/scrutinizer/g/renanwilliam/hangouts-chat.svg?style=flat-square)](https://scrutinizer-ci.com/g/renanwilliam/hangouts-chat)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/renanwilliam/hangouts-chat/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/renanwilliam/hangouts-chat/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/hangouts-chat.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/hangouts-chat)

This package makes it easy to send notifications using [Google Hangouts Chat](https://gsuite.google.com/products/chat/) with Laravel 5.5+, 6.x and 7.x

## Contents

- [Installation](#installation)
	- [Setting up the Google Hangouts Chat service](#setting-up-the-google-hangouts-chat-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install this package with Composer:

```bash
composer require laravel-notification-channels/hangouts-chat
```


### Setting up the Google Hangouts Chat service

In order to send messages using bots, you need to [setup a webhook for a room](https://developers.google.com/hangouts/chat/how-tos/webhooks#send_messages_to_the_chat_room).

## Usage
Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\GoogleHangoutsChat\HangoutsChatChannel;
use NotificationChannels\GoogleHangoutsChat\HangoutsChatMessage;
use Illuminate\Notifications\Notification;

class ProjectCreated extends Notification
{
    public function via($notifiable)
    {
        return ['hangoutsChat'];
    }

    public function toHangoutsChat$notifiable)
    {
        return HangoutsChatMessage::create()
            ->data([
               'text' => 'It is a test message!'
            ]);
    }
}
```
Please look at [Hangouts Chat Message Formats](https://developers.google.com/hangouts/chat/reference/message-formats) to understand how send the messages.

In order to let your Notification know which URL should receive the Webhook data, add the `routeNotificationForHangoutsChat` method to your Notifiable model.
This method needs to return the URL where the notification Webhook will receive a POST request.


```php
public function routeNotificationForHangoutsChat()
{
    return 'https://chat.googleapis.com/v1/spaces/XXXXX-XXXXX/messages?key={key}&token={token}';
}
```

You can send to a dynamic Webhook URL on-fly using this syntax:
```php
Notification::route('hangoutsChat', 'https://chat.googleapis.com/v1/spaces/XXXXX-XXXXX/messages?key={key}&token={token}')
                    ->notify(new NotificationClass($params));
```

### Available Message methods

- `data('')`: Accepts a JSON-encodable value for the Webhook body.
- `query('')`: Accepts an associative array of query string values to add to the request.
- `userAgent('')`: Accepts a string value for the Webhook user agent.
- `header($name, $value)`: Sets additional headers to send with the POST Webhook.
- `verify()`: Enable the SSL certificate verification or provide the path to a CA bundle

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email renan@4success.com.br instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Renan William Alves de Paula](https://github.com/renanwilliam)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
