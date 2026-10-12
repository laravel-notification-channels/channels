# IssueBadge notification channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/issuebadge.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/issuebadge)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/github/actions/workflow/status/laravel-notification-channels/issuebadge/tests.yml?branch=master&style=flat-square)](https://github.com/laravel-notification-channels/issuebadge/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/issuebadge.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/issuebadge)

This package makes it easy to issue digital certificates and badges with
[IssueBadge](https://issuebadge.com) from Laravel notifications. When a user
completes a course, passes an exam or attends an event, send them a notification
and the channel creates the credential on IssueBadge, which emails the recipient
their certificate and public verification page.

```php
public function toIssueBadge($notifiable): IssueBadgeMessage
{
    return IssueBadgeMessage::create('badge_id_from_issuebadge')
        ->expireDate(now()->addYear())
        ->meta('score', $this->score);
}
```

## Contents

- [Installation](#installation)
    - [Setting up the IssueBadge service](#setting-up-the-issuebadge-service)
- [Usage](#usage)
    - [Routing the recipient](#routing-the-recipient)
    - [On-demand notifications](#on-demand-notifications)
    - [Available Message methods](#available-message-methods)
    - [Handling failures](#handling-failures)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

```bash
composer require laravel-notification-channels/issuebadge
```

The service provider is auto-discovered.

### Setting up the IssueBadge service

1. Sign in to [app.issuebadge.com](https://app.issuebadge.com), create a badge template
   (the certificate design) and note its badge ID. You can also list badges with
   `GET /api/v1/badge/getall`.
2. Create an API key under **Developer → API keys**.
3. Add the credentials to `config/services.php`:

```php
'issuebadge' => [
    'key' => env('ISSUEBADGE_API_KEY'),
    // Optional, for self-hosted or staging installations:
    'url' => env('ISSUEBADGE_URL', 'https://app.issuebadge.com'),
],
```

## Usage

Return the channel from `via()` and describe the credential in `toIssueBadge()`:

```php
use Illuminate\Notifications\Notification;
use NotificationChannels\IssueBadge\IssueBadgeChannel;
use NotificationChannels\IssueBadge\IssueBadgeMessage;

class CourseCompleted extends Notification
{
    public function __construct(private Course $course, private int $score)
    {
    }

    public function via($notifiable): array
    {
        return [IssueBadgeChannel::class]; // or 'issuebadge'
    }

    public function toIssueBadge($notifiable): IssueBadgeMessage
    {
        return IssueBadgeMessage::create($this->course->issuebadge_badge_id)
            ->issueDate(now())
            ->expireDate(now()->addYears(2))
            ->idempotencyKey("course-{$this->course->id}-user-{$notifiable->id}")
            ->metadata(['score' => $this->score, 'course' => $this->course->title]);
    }
}
```

```php
$user->notify(new CourseCompleted($course, 95));
```

The recipient name and email default to the notifiable's `name` and `email`
attributes. `toIssueBadge()` may also return just the badge ID string when no
other options are needed.

### Routing the recipient

Override the recipient per model with `routeNotificationForIssuebadge()`. Return an
email string, or an array with `name` and `email`:

```php
public function routeNotificationForIssuebadge($notification): array
{
    return ['name' => $this->full_name, 'email' => $this->work_email];
}
```

Values set on the message (`->name()`, `->email()`) always win over the route and
the model attributes.

### On-demand notifications

```php
use Illuminate\Support\Facades\Notification;

Notification::route('issuebadge', ['name' => 'Grace Hopper', 'email' => 'grace@example.com'])
    ->notify(new CourseCompleted($course, 98));
```

### Available Message methods

- `IssueBadgeMessage::create(?string $badgeId)` / `new IssueBadgeMessage(?string $badgeId)`
- `badge(string $badgeId)`: the badge template to issue (required)
- `name(string $name)`: recipient name printed on the certificate
- `email(?string $email)`: recipient email; IssueBadge emails the certificate when set
- `issueDate(DateTimeInterface|string $date)`: `Y-m-d`, defaults to today
- `expireDate(DateTimeInterface|string $date)`: `Y-m-d`
- `idempotencyKey(string $key)`: re-sending the same key returns the existing issue instead of a duplicate
- `metadata(array $values)` / `meta(string $key, mixed $value)`: custom field values defined on the badge template
- `toArray()`: the request body sent to `POST /api/v1/issue/create`

`IssueBadgeChannel::send()` returns the API response, including `IssueId` and
`publicUrl` (the verification page), so you can store them from a custom channel
wrapper or a notification event listener.

### Handling failures

The channel throws `NotificationChannels\IssueBadge\Exceptions\CouldNotSendNotification`
when the badge ID or recipient name is missing, when IssueBadge rejects the request
(the HTTP status is the exception code and validation errors are listed in the
message), or when the API cannot be reached. A missing API key throws
`InvalidConfiguration` when the channel is resolved.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
composer test
```

## Security

If you discover any security related issues, please email product@issuebadge.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [IssueBadge](https://github.com/issuebadge2026)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
