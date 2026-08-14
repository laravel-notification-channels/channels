# OneWaySMS notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/onewaysms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/onewaysms)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![run-tests](https://github.com/laravel-notification-channels/onewaysms/actions/workflows/run-tests.yml/badge.svg)](https://github.com/laravel-notification-channels/onewaysms/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/onewaysms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/onewaysms)

Send SMS notifications through [OneWaySMS](https://www.onewaysms.com.my), a Malaysian SMS gateway.

```php
public function toOneWaySms($notifiable)
{
    return OneWaySmsMessage::create('Your OTP is 123456');
}
```

## Contents

- [Installation](#installation)
- [Setting up the OneWaySMS service](#setting-up-the-onewaysms-service)
- [Usage](#usage)
- [Available message methods](#available-message-methods)
- [Checking your credit balance](#checking-your-credit-balance)
- [Error handling](#error-handling)
- [Delivery notifications](#delivery-notifications)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

```bash
composer require laravel-notification-channels/onewaysms
```

## Setting up the OneWaySMS service

Log in to your OneWaySMS account and open the API section. It gives you an API
username and password — **these differ from your web login** — and the MT and
credit URLs assigned to your account.

Add the credentials to `config/services.php`:

```php
'onewaysms' => [
    'endpoint' => env('ONEWAYSMS_ENDPOINT', 'https://gateway.onewaysms.com.my:10001'),
    'username' => env('ONEWAYSMS_API_USERNAME'),
    'password' => env('ONEWAYSMS_API_PASSWORD'),
    'sender'   => env('ONEWAYSMS_SENDER_ID'),
],
```

`endpoint` is the scheme, host, and port only — the package appends `/api.aspx`
and `/bulkcredit.aspx`. The gateway listens on port 10001 or port 80. If your
account's URLs differ, set `ONEWAYSMS_ENDPOINT` to match.

The default, `https://gateway.onewaysms.com.my:10001`, is **inferred** — the
vendor's own documentation never prints a literal host. Confirm the URLs shown
in your account's API section rather than assuming the default is correct.

Sender IDs may contain at most 11 alphanumeric characters. OneWaySMS notes that
sender IDs are no longer honoured in Malaysia; using `INFO` yields a 5-digit
number beginning with 6.

## Usage

Add `one_way_sms` to the notification's `via()` and implement `toOneWaySms()`:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\OneWaySms\OneWaySmsMessage;

class OrderShipped extends Notification
{
    public function via($notifiable)
    {
        return ['one_way_sms'];
    }

    public function toOneWaySms($notifiable)
    {
        return OneWaySmsMessage::create('Your order has shipped.');
    }
}
```

`via()` must return the string `'one_way_sms'`, as above — not
`OneWaySmsChannel::class`. The class name also resolves, via Laravel's
container autowiring, but it silently sends nothing: it bypasses the
`one_way_sms` driver registered in the service provider, so there is no
matching `routeNotificationForOneWaySms()` lookup and the configured sender
is never applied.

Returning a plain string works too:

```php
public function toOneWaySms($notifiable)
{
    return 'Your order has shipped.';
}
```

Route notifications by adding `routeNotificationForOneWaySms()` to the notifiable.
Numbers must include the country code:

```php
public function routeNotificationForOneWaySms()
{
    return '60121234567';
}
```

Return an array to send one request to several recipients. OneWaySMS accepts at
most **10** numbers per request:

```php
public function routeNotificationForOneWaySms()
{
    return ['60121234567', '60197654321'];
}
```

## Available message methods

| Method | Description |
| --- | --- |
| `create(string $content)` | Build a message. |
| `content(string $content)` | Set the message body. |
| `sender(string $sender)` | Override the configured sender ID. |
| `unicode()` | Force Unicode encoding (`languagetype=2`). |
| `text()` | Force plain text encoding (`languagetype=1`). |

`languageType()` and `encodedContent()` are also public, but they exist for the
channel to build the outgoing request — the channel calls them for you, so you
won't normally need to call them yourself.

### Unicode

Encoding is chosen automatically: any non-ASCII character switches the message to
Unicode and hex-encodes it as UTF-16BE, exactly as the gateway requires. Call
`text()` or `unicode()` to override.

Encoding affects cost, because it changes how many characters fit in one SMS:

| Encoding | Characters per part |
| --- | --- |
| Text (`languagetype=1`) | 160 |
| Unicode (`languagetype=2`) | 70 |

Concatenated messages spend 7 characters per part on joining information, so
2 parts carry 306 text characters and 3 parts carry 459. OneWaySMS recommends
sending no more than 3 parts. This package does **not** enforce that — it is a
recommendation, not an API limit — so keep an eye on message length yourself.

## Checking your credit balance

```php
use NotificationChannels\OneWaySms\OneWaySmsApi;

$balance = app(OneWaySmsApi::class)->checkBalance();
```

## Error handling

Every failure throws `NotificationChannels\OneWaySms\Exceptions\CouldNotSendNotification`.
When the failure came from the gateway, `gatewayCode()` returns its raw code:

```php
use NotificationChannels\OneWaySms\Exceptions\CouldNotSendNotification;

try {
    $user->notify(new OrderShipped());
} catch (CouldNotSendNotification $e) {
    report($e);

    if ($e->gatewayCode() === -600) {
        // Out of credit.
    }
}
```

| Code | Meaning |
| --- | --- |
| `-100` | API username or password invalid |
| `-200` | Sender ID invalid |
| `-300` | Recipient number invalid |
| `-400` | Language type invalid |
| `-500` | Message contains invalid characters |
| `-600` | Insufficient credit balance |

`gatewayCode()` returns `null` when the failure carried no gateway error code —
a pre-flight validation failure (an empty message, a missing sender, or more
than 10 recipients), a transport failure, or an unparseable response.

## Delivery notifications

OneWaySMS reports delivery by calling a DN URL you configure in your account.
It sends two query parameters, `mtid` and `status`, where `status` is `1` for
success and `-1` for failure. Receiving that callback is a routing concern, so
this package leaves it to you:

```php
Route::get('/onewaysms/dn', function (Request $request) {
    $mtId = $request->query('mtid');
    $delivered = (int) $request->query('status') === 1;

    // Record the result, then answer 200.

    return response('OK');
});
```

To match delivery notifications against the request that sent them, you need
the MT IDs the gateway returned. `$notifiable->notify(...)` doesn't return them
directly — Laravel discards the plain return value — but it does hand them to
your notification through two other routes.

The value is available on the `NotificationSent` event as `$response`. This
works on every Laravel version this package supports, so it's the reliable
option:

```php
use Illuminate\Notifications\Events\NotificationSent;

Event::listen(function (NotificationSent $event) {
    if ($event->channel === 'one_way_sms') {
        // $event->response is an array<int, string> of MT IDs, or null.
    }
});
```

Laravel 12.51+ and 13.x also let you define `afterSending()` directly on the
notification, and Laravel calls it with the channel's response, which for
this channel is the MT ID array (or `null` when the notifiable had no route):

```php
class OrderShipped extends Notification
{
    // ...

    public function afterSending($notifiable, string $channel, mixed $response): void
    {
        if ($channel === 'one_way_sms') {
            // $response is an array<int, string> of MT IDs, or null.
            Log::info('OneWaySMS MT IDs', ['ids' => $response]);
        }
    }
}
```

> **Version note:** `afterSending()` was added to `laravel/framework` in
> 12.51.0 and is present in all 13.x releases. It is **absent** on 10.x, 11.x,
> and 12.x before 12.51 — which this package's `composer.json` also supports
> — so on those versions the method above compiles and runs but is never
> called. Use the `NotificationSent` listener above if you need to support
> earlier versions.

To send outside the notification system and get the MT IDs back as an ordinary
return value, call the channel through Laravel's notification driver resolver:

```php
use Illuminate\Support\Facades\Notification;

$mtIds = Notification::driver('one_way_sms')->send($user, new OrderShipped());
```

Or bypass notifications entirely and call `OneWaySmsApi::send()` yourself. Its
signature takes the sender explicitly, so it needs no sender configuration:

```php
use NotificationChannels\OneWaySms\OneWaySmsApi;

$mtIds = app(OneWaySmsApi::class)->send([
    'to' => '60121234567',
    'sender' => config('services.onewaysms.sender'),
    'languagetype' => 1,
    'message' => 'Your order has shipped.',
]);
```

Called this way, `OneWaySmsApi::send()` sends `message` to the gateway
exactly as given — it does not perform the automatic text/Unicode selection
or hex encoding that `OneWaySmsMessage` and the channel do for you. If you
pass `languagetype => 2` (Unicode) directly, you must hex-encode `message`
yourself, or build both values with `OneWaySmsMessage`:

```php
$text = OneWaySmsMessage::create('Ujian 测试')->unicode();

$mtIds = app(OneWaySmsApi::class)->send([
    'to' => '60121234567',
    'sender' => config('services.onewaysms.sender'),
    'languagetype' => $text->languageType(),
    'message' => $text->encodedContent(),
]);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for what has changed recently.

## Testing

```bash
composer test
```

## Security

OneWaySMS authenticates via query parameters (`apiusername`/`apipassword`)
rather than a header — this is inherent to the vendor's API, not a choice this
package makes. That means credentials appear in the request URL and can reach
proxy access logs, any Guzzle logging middleware your application binds, and
similar places a header wouldn't. It's also why `CouldNotSendNotification`
redacts credentials, the recipient number, and the message body from its
exception messages before they can reach `report()` or your logs.

If you discover any security related issues, please email hafiqiqmal93@gmail.com
instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Hafiq Iqmal](https://github.com/afiqiqmal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
