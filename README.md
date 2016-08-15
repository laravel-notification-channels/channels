# PubNub Notifications Channel for Laravel 5.3 [WIP]

PubNub Notifications Channel for Laravel 5.3

## Contents

- [Installation](#installation)
	- [Setting up the PubNub service](#setting-up-the-PubNub-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

```bash
composer require laravel-notification-channels/pubnub
```

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Pubnub\PubnubServiceProvider::class,
],
```

### Setting up the PubNub service

Add your PubNub Publish Key, Subscribe Key and Secret Key to your `config/services.php`:

```php
// config/services.php
...

'pubnub' => [
    'publish_key'   => env('PUBNUB_PUBLISH_KEY'),
    'subscribe_key' => env('PUBNUB_SUBSCRIBE_KEY'),
    'secret_key'    => env('PUBNUB_SECRET_KEY'),
],

... 
```

## Usage

Some code examples, make it clear how to use the package

### Available methods

A list of all available options

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email wade@iwader.co.uk instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [iWader](https://github.com/iWader)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
