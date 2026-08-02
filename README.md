# sms.ir notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/sms-ir.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/sms-ir)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/sms-ir.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/sms-ir)

This package makes it easy to send notifications using [sms.ir](https://sms.ir), an
Iranian SMS provider, with Laravel.

It is a thin adapter over [`farsi/laravel-smsir`](https://github.com/farsidev/laravel-smsir),
which handles the API itself.

## Contents

- [Installation](#installation)
    - [Setting up the sms.ir service](#setting-up-the-smsir-service)
- [Usage](#usage)
    - [Sending a one-time password](#sending-a-one-time-password)
    - [Available message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

```bash
composer require laravel-notification-channels/sms-ir
```

The service provider of the underlying client is discovered automatically.

### Setting up the sms.ir service

Publish its configuration:

```bash
php artisan vendor:publish --tag=smsir-config
```

Then add your credentials:

```dotenv
SMSIR_API_KEY=your-api-key
SMSIR_LINE_NUMBER=30007xxxxxxx
```

Both are issued from the [sms.ir panel](https://app.sms.ir).

## Usage

Use the channel in the `via()` method of your notification:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\SmsIr\SmsIrChannel;
use NotificationChannels\SmsIr\SmsIrMessage;

class OrderShipped extends Notification
{
    public function via($notifiable): array
    {
        return [SmsIrChannel::class];
    }

    public function toSmsIr($notifiable): SmsIrMessage
    {
        return SmsIrMessage::create('سفارش شما ارسال شد.');
    }
}
```

In your notifiable model, add a `routeNotificationForSmsIr()` method returning a phone
number or an array of phone numbers:

```php
public function routeNotificationForSmsIr()
{
    return $this->phone_number;
}
```

Returning `null` is fine — a notifiable with no number is skipped rather than treated as
an error. Numbers may be given as `09120000000`, `9120000000`, `+989120000000` or
`00989120000000`; they are normalised before sending.

### Sending a one-time password

Iranian regulation requires one-time passwords to be sent on a *template* line rather
than as free text, so OTP goes through sms.ir's verification endpoint:

```php
public function toSmsIr($notifiable): SmsIrMessage
{
    return SmsIrMessage::verify(100000)->with(['CODE' => $this->code]);
}
```

`100000` is the template id (`شناسه قالب`) shown in the panel after you create a
template. The template body uses placeholders written between hashes — `#CODE#` — while
the parameter here is the bare name, `CODE`. The two must agree.

You can name templates in `config/smsir.php` and refer to them by name instead:

```php
// config/smsir.php
'templates' => [
    'otp' => env('SMSIR_TEMPLATE_OTP'),
],
```

```php
return SmsIrMessage::verify('otp')->with(['CODE' => $this->code]);
```

> A newly created template receives its id immediately but stays at
> `وضعیت تایید: در حال بررسی` until sms.ir approves it. Sends against an unapproved
> template are rejected, so an id in hand is not yet a working template.

Persian text is sent as UCS-2, so **one SMS part is 70 characters**. The panel counts
this live as you type the template.

### Available message methods

`SmsIrMessage::create($content)`: a plain-text message.

`SmsIrMessage::verify($template)`: a verification (template) message. Takes a numeric
template id or a name from `smsir.templates`.

`content($content)`: set the message content.

`with(array $parameters)`: set template parameters, merged across calls.

`from($line)`: send from a specific line number instead of the configured one.

`sendAt($dateTime)`: schedule the message rather than sending it now.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for what has changed recently.

## Testing

```bash
composer test
```

## Security

If you discover a security vulnerability, please email ali@farsi.dev rather than opening
an issue.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Ali Sameni](https://github.com/aliwesome)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
