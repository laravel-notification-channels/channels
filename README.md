# Linkedin notification channel for Laravel 5.3+


This package makes it easy to send notifications using [Linkedin](https://www.linkedin.com/developer/apps) with Laravel 5.3+.

## Contents

- [Installation](#installation)
- [Setting up the Linkedin service](#setting-up-the-linkedin-service)
- [Usage](#usage)
	- [Publish post update](#publish-linkedin-status-update)
 - [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install this package via composer:

``` bash
composer require khaninejad/linkedin@dev-master
```

Next add the service provider to your `config/app.php`:

```php
...
'providers' => [
	...
	 khaninejad\linkedin\LinkedinServiceProvider::class,
],
...
```



### Setting up the Linkedin service

You will need to [create](https://www.linkedin.com/developer/apps) a Linkedin app in order to use this channel. Within in this app you will find the `keys and access tokens`. Place them inside your `.env` file. In order to load them, add this to your `config/services.php` file:

```php
...
'linkedin' => [
	'client_id'    => env('LINKEDIN_KEY'),
	'client_secret' => env('LINKEDIN_SECRET'),
	'redirect'    => env('LINKEDIN_REDIRECT_URI'),
	'access_token'   => env('LINKEDIN_Access_TOKEN')
]
...
```

This will load the Linkedin app data from the `.env` file. Make sure to use the same keys you have used there like `LINKEDIN_KEY`.

## Usage

Follow Laravel's documentation to add the channel to your Notification class.

### Publish Linkedin status update

```php
use khaninejad\linkedin\LinkedinChannel;
use khaninejad\linkedin\LinkedinMessage;

class NewsWasPublished extends Notification
{

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [LinkedinChannel::class];
    }

    public function toLinkedin($notifiable)
    {
        return new LinkedinMessage('Laravel notifications are awesome!');
    }
}
```

Take a closer look at the `StatusUpdate` object. This is where the magic happens.
````php
public function toLinkedin($notifiable)
{
    return new LinkedinMessage('Laravel notifications are awesome!');
}
````


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please use the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [happyr/linkedin-api-client](https://github.com/Happyr/LinkedIn-API-client)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
