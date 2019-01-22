# Pushmix notifications channel for Laravel 5.3+

This package makes it easy to send [Pushmix notifications](https://www.pushmix.co.uk/docs/laravel-notification-channel) with Laravel 5.3+.

## Contents

- [Setting up your Pushmix account](#setting-up-your-pushmix-account)
- [Installation](#installation)
	- [Configuration](#configuration)
- [Usage](#usage)
	- [Available Message methods](#all-available-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Setting up your Pushmix account

If you haven't already, sign up for a free account on [pushmix.co.uk](https://dash.pushmix.co.uk/register).

[Create new subscription](https://www.pushmix.co.uk/docs/create-subscription) for your website and choose preferred [integration method](https://www.pushmix.co.uk/docs/installation). Build your subscribers audience via displaying an Opt-in Prompt and asking users for permission to send them push notifications.

## Installation

You can install the package via composer:

```bash
$ composer require laravel-notification-channels/pushmix
```

If you're installing the package in Laravel 5.4 or lower, you must import the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\OneSignal\PushmixServiceProvider::class,
],
```

### Configuration

Add your Pushmix Subscription ID to your `config/services.php`:

```php
// config/services.php
...
'pushmix' => [
    'key' => env('PUSHMIX_ID'),
],
...
```


## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\Pushmix\PushmixChannel;
use NotificationChannels\Pushmix\PushmixMessage;
use Illuminate\Notifications\Notification;

class AbandonedCart extends Notification
{
    public function via($notifiable)
    {
        return [PushmixChannel::class];
    }

		public function toPushmix($to)
    {

      return PushmixMessage::create($to)
						/* Required Parameters */
          ->title("You still have items in your Cart!")
          ->body("There's still time to complete your order. Return to your cart?")
          ->url("https://www.pushmix.co.uk")

					/* Optional Parameters */
          ->button("Return to your cart", "https://www.pushmix.co.uk/docs") // button one
          ->priority("high")
          ->ttl(7200) // time to live
          ->icon("https://www.pushmix.co.uk/media/favicons/apple-touch-icon.png")
          ->badge("https://www.pushmix.co.uk/media/favicons/pm_badge_v2.png")
          ->image("https://www.pushmix.co.uk/media/photos/photo16.jpg");
    }
}
```

The notifications can be sent to your audience, which subscribed via opt-in prompt displayed on your website.
Using the `Notification::route` method, you can specify which subscribers group you are targeting.

```php
use Notification;
use App\Notifications\AbandonedCart;
...
// Target All Subscribed Users
Notification::route('Pushmix', 'all')->notify(new AbandonedCart());

// Target Topic One Subscribers
Notification::route('Pushmix', 'one')->notify(new AbandonedCart());

// Target Topic Two Subscribers
Notification::route('Pushmix', 'two')->notify(new AbandonedCart());


```
### All available methods

[Pushmix documentation](https://www.pushmix.co.uk/docs/api)

- `title('')`: Accepts a string value for the title, required*
- `body('')`: Accepts a string value for the notification body,required*
- `url('')`: Accepts an url for the notification click event,required*

- `button('', '')`: Accepts string value for button title and an url for the notification click event. Max 2 buttons can be attached.
- `icon('')`: Accepts an url for the icon.
- `priority('')`: Accepts `high` or `normal` strings.
- `ttl('')`: Accepts an integer, notification life span in seconds,must be from 0 to 2,419,200
- `icon('')`: Accepts an url for the icon.
- `badge('')`: Accepts an url for the badge.
- `image('')`: Accepts an url for the large image.


## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please email alexpechkarev@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Alexander Pechkarev](https://github.com/alexpechkarev)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
