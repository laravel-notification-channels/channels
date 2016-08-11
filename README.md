# Pushover notifications channel for Laravel 5.3 [WIP]

This package makes it easy to send Pushover notifications with Laravel 5.3.


## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/pushover-notifications
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\PushoverNotifications\Provider::class,
];
```

To start sending messages via Pushover, you have to [register an application](https://pushover.net/apps/build).
Add the generated Pushover application token to the services config file:
```php
// config/services.php
...
'pushover' => [
    'token' => 'YOUR_APPLICATION_TOKEN',
];
...
```

## Usage

Now you can use the channel in your `via()` method inside the notification as well as send a push notification:

``` php
use NotificationChannels\PushoverNotifications\Channel as PushoverChannel;
use NotificationChannels\PushoverNotifications\Message as PushoverMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [PushoverChannel::class];
    }

    public function toPushover($notifiable)
    {
        return (new PushoverMessage)
            ->content('The invoice has been paid.')
            ->sound('incoming')
            ->title('Invoice paid')
            ->highPriority()
            ->url('http://example.com/invoices', 'Go to your invoices')
            ->time(Carbon::now()->addHour(1));
    }
}
```

Make sure there is a `routeNotificationForPushover` method on your notifiable model, for instance:
``` php
...
public function routeNotificationForPushover()
{
    return $this->pushover;
}
```


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing
    
``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email mail@casperboone.nl instead of using the issue tracker.

## Credits

- [Casper Boone](https://github.com/casperboone)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
