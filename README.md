# InterFAX notification channel for Laravel 5.8+ and 6.x

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ivinteractive/laravel-interfax-notification-channel.svg?style=flat-square)](https://packagist.org/packages/ivinteractive/laravel-interfax-notification-channel)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/ivinteractive/laravel-interfax-notification-channel/master.svg?style=flat-square)](https://travis-ci.org/ivinteractive/laravel-interfax-notification-channel)
[![StyleCI](https://styleci.io/repos/217342993/shield)](https://styleci.io/repos/217342993)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/5c642897-a39d-4a62-8618-2880d818b101.svg?style=flat-square)](https://insight.sensiolabs.com/projects/5c642897-a39d-4a62-8618-2880d818b101)
[![Quality Score](https://img.shields.io/scrutinizer/g/ivinteractive/laravel-interfax-notification-channel.svg?style=flat-square)](https://scrutinizer-ci.com/g/ivinteractive/laravel-interfax-notification-channel)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ivinteractive/laravel-interfax-notification-channel/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/ivinteractive/laravel-interfax-notification-channel/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/ivinteractive/laravel-interfax-notification-channel.svg?style=flat-square)](https://packagist.org/packages/ivinteractive/laravel-interfax-notification-channel)

This package makes it easy to send notifications using [InterFAX](https://interfax.net) with Laravel 5.8+ and 6.0

## Contents

- [Installation](#installation)
  - [Setting up the InterFAX service](#setting-up-the-InterFAX-service)
- [Usage](#usage)
  - [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

You can install this package via composer:

```bash
composer require laravel-notification-channels/interfax
```

The service provider gets loaded automatically.

### Setting up the InterFAX service

This channel will use your InterFAX username and password. To use the channel, add this to your `config/services.php` file:

```php
...
'interfax' => [
  'username' => env('INTERFAX_USERNAME'),
  'password' => env('INTERFAX_PASSWORD'),
  'pci'      => env('INTERFAX_PCI', false),
],
...
```

This will load your InterFAX credentials from the `.env` file. If your requests must be PCI-DSS-compliant, set `INTERFAX_PCI=true` in your `.env` file.

## Usage

To use this package, you can create a notification class, like `DocumentWasSent` from the example below, in your Laravel application. Make sure to check out [Laravel's documentation](https://laravel.com/docs/master/notifications) for this process.

### Send PDF via fax

```php
<?php
use NotificationChannels\Interfax\InterfaxChannel;
use NotificationChannels\Interfax\InterfaxMessage;

class DocumentWasSent extends Notification
{

    protected $files;

    public function __construct(array $files)
    {
    $this->files = $files;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [InterfaxChannel::class];
    }

    public function toInterfax($notifiable)
    {
        return (new InterfaxMessage)
              ->files($this->files);
    }
}
```

The Notifiable model will need to return a destination fax number.

```php
public function routeNotificationForInterfax($notification)
{
    if($this->fax)
        return preg_replace('/[^\d]/', '', $this->fax);

    return null;
}
```

### Available Message methods

`file(string $file)` : Accepts the full path to a single file (full list of supported file types [found here](https://www.interfax.net/en/help/supported_file_types)).  
`files(array $array)` : Accepts an array of file paths.  
`stream(Filestream $stream, string $name)` : Accepts a file stream.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email craig.spivack@ivinteractive.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Craig Spivack](https://github.com/iv-craig)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
