# Discord Webhook Notifications Channel for Laravel 5.3 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/discord-webhook.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/discord-webhook)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/discord-webhook/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/discord-webhook)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/discord-webhook.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/discord-webhook)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/discord-webhook/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/discord-webhook/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/discord-webhook.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/discord-webhook)
[![Discord](https://discordapp.com/api/guilds/240540496068476928/widget.png)](https://discord.gg/9RP6RPg)

This package makes it easy to send notifications using [Discord Webhook](https://support.discordapp.com/hc/en-us/articles/228383668-Using-Webhooks) with Laravel 5.3.

## Contents

- [Installation](#installation)
	- [Setting up the Discord Webhook service](#setting-up-your-discord-webhook)
- [Usage](#usage)
    - [Routing a message](#routing-a-message)
    - [Send a message with embeds](#send-a-message-with-embeds)
    - [Send a message with file upload](#send-a-message-with-file-upload)
	- [Available Message methods](#available-message-methods)
	- [Available Embed methods](#available-embed-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/discord-webhook
```

### Setting up your Discord Webhook

Follow the official guide [Using Webhook](https://support.discordapp.com/hc/en-us/articles/228383668-Using-Webhooks) to set up your Discord Webhook.

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use Illuminate\Notifications\Notification;
use NotificationChannels\DiscordWebhook\DiscordWebhookChannel;
use NotificationChannels\DiscordWebhook\DiscordWebhookMessage;

class Application extends Notification
{
    public function via($notifiable)
    {
        return [DiscordWebhookChannel::class];
    }

    public function toDiscordWebhook($notifiable)
    {
        return (new DiscordWebhookMessage())
            ->content('Your guild received a new membership application!');
    }
}
```

### Routing a message

In order to let your Notification know which Webhook (Discord Channel) you are targeting, add the `routeNotificationForDiscordWebhook` method to your Notifiable model:

``` php
public function routeNotificationForDiscordWebhook()
{
    return 'https://discordapp.com/api/webhooks/{webhook.id}/{webhook.token}';
}
```

Add `?wait=true` to your Webhook URL, to receive the sent message:

``` php
return 'https://discordapp.com/api/webhooks/{webhook.id}/{webhook.token}?wait=true';
```

### Send a message with embeds

Discord Webhook allows you to add embedded rich content to your message:

``` php
public function toDiscordWebhook($notifiable)
{
    return (new DiscordWebhookMessage())
        ->from('Raid Calendar')
        ->content('**Next Raids**')
        ->embed(function ($embed) {
            $embed->title('Dragon Dungeon')->description('*on Monday*')
                ->field('Raid Leader', 'TheTank', true)
                ->field('Co-Leader', 'HealMePls', true);
        });
}
```

### Send a message with file upload

Discord Webhook allows you to upload a file with your message:

``` php
public function toDiscordWebhook($notifiable)
{
    return (new DiscordWebhookMessage())
        ->content('__Member of the Day:__')
        ->file(\Storage::get('motd_avatar.png'), 'member_of_the_day.png');
}
```

### Available Message methods

- `content($text)`: The message contents (up to 2000 characters).
- `from($username[, $avatar_url])`: Override the default username and avatar (optional) of the webhook.
- ~~- `tts()`: Send as a TTS message.~~ (Does currently not work for Webhooks)
- `file($content, $filename)`: The contents of the file being sent. **NOTE:** Does not work with embedded rich content
- `embed($callback)`: Define (up to 10) embedded rich content for the message. (see [Example](#send-a-message-with-embeds) and [Embeds](#available-embed-methods))

### Available Embed methods

- `title($title[, $url])`: Set the title of embed.
- `description($text)`: Set the description of embed.
- `color($color_int)`: Set the color code of the embed.
- `footer($text[, $icon_url])`: Set the footer information.
- `image($img_url)`: Set the image information.
- `thumbnail($img_url)`: Set the thumbnail information.
- `author($name[, $url, $icon_url])`: Set the author information.
- `field($name, $value[, $inline_bool])`: Set the (inline) fields information.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email drdrjojo2k@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Dr. Dr. Jojo](https://github.com/drdrjojo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
