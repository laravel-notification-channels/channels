Please see [this repo](https://github.com/laravel-notification-channels/channels) for instructions on how to submit a channel proposal.

# Ntfy

This package makes it easy to send notifications using [ntfy](link to service) with Laravel 5.5+, 6.x and 7.x

## Contents

- [Installation](#installation)
    - [Setting up the ntfy service](#setting-up-the-ntfy-service)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install the package via composer:

```bash
composer require laravel-notification-channels/ntfy
```
```php
// config/services.php
'providers' => [
    // ...
    NotificationChannels\Ntfy\NtfyServiceProvider::class,
],
```

### Setting up the ntfy service

Optionally include a few steps how users can set up the service.

## Usage

Some code examples, make it clear how to use the package

### Available Message methods

A list of all available options

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email onkal.cengiz@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Cengiz Onkal](https://github.com/cengizonkal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
