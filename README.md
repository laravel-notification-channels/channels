# kwtSMS Notification Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kwtsms/kwtsms-notification-channel.svg?style=flat-square)](https://packagist.org/packages/kwtsms/kwtsms-notification-channel)
[![Total Downloads](https://img.shields.io/packagist/dt/kwtsms/kwtsms-notification-channel.svg?style=flat-square)](https://packagist.org/packages/kwtsms/kwtsms-notification-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

This package makes it easy to send notifications using [kwtSMS](https://www.kwtsms.com) with Laravel.

## Contents

- [Installation](#installation)
- [Setting up kwtSMS](#setting-up-kwtsms)
- [Usage](#usage)
- [Available Methods](#available-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via Composer:

```bash
composer require kwtsms/kwtsms-notification-channel
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="NotificationChannels\KwtSms\KwtSmsServiceProvider" --tag="config"
```

Add your kwtSMS credentials to your `.env` file:

```dotenv
KWTSMS_USERNAME=your-username
KWTSMS_PASSWORD=your-password
KWTSMS_SENDER=your-sender-id
KWTSMS_TEST_MODE=false
```

## Setting up kwtSMS

To get started, you need a kwtSMS account:

1. Sign up at [kwtsms.com](https://www.kwtsms.com)
2. Obtain your API username and password from the dashboard
3. Register a Sender ID (or use the default `KWT-SMS`)
4. Add the credentials to your `.env` file as shown above

## Usage

Create a notification class that uses the `KwtSmsChannel`:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\KwtSms\KwtSmsChannel;
use NotificationChannels\KwtSms\KwtSmsMessage;

class OrderShipped extends Notification
{
    public function via($notifiable): array
    {
        return [KwtSmsChannel::class];
    }

    public function toKwtSms($notifiable): KwtSmsMessage
    {
        return KwtSmsMessage::create("Your order #{$this->order->id} has been shipped!");
    }
}
```

Add the `routeNotificationForKwtSms` method to your notifiable model:

```php
public function routeNotificationForKwtSms($notification): string
{
    return $this->phone; // International format: 96598765432
}
```

You can also return a plain string from `toKwtSms` instead of a `KwtSmsMessage` object:

```php
public function toKwtSms($notifiable): string
{
    return 'Your order has been shipped!';
}
```

## Available Methods

### KwtSmsMessage

| Method | Description |
|---|---|
| `create(string $content = '')` | Static factory to create a new message |
| `content(string $content)` | Set the message content |
| `sender(string $sender)` | Override the default sender ID |
| `getContent()` | Get the message content |
| `getSender()` | Get the sender ID (null if not set) |

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please use the issue tracker on GitHub instead of sending an email.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [BoxLink](https://github.com/boxlinknet)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
