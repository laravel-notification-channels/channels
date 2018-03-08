# Asana notifications channel for Laravel 5.6

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/asana.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/asana)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/asana/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/asana)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/asana.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/asana)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/asana/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/asana/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/asana.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/asana)

This package makes it easy to send notifications using [Asana](https://app.asana.com) with Laravel 5.6.

## Contents

- [Installation](#installation)
    - [Setting up the Asana service](#setting-up-the-asana-service)
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

``` bash
composer require laravel-notification-channels/asana
```

### Setting up the Asana service

Once installed you need to register the service provider with the application. Open up `config/app.php` and find the `providers` key.

``` php
'providers' => [

    \Torann\LaravelAsana\ServiceProvider::class,

]
```

Run this on the command line from the root of your project:

```
$ php artisan vendor:publish --provider="Torann\LaravelAsana\ServiceProvider" --tag=config
```

A configuration file will be publish to `config/asana.php`.

Add your Asana Token to `.env`

```
ASANA_TOKEN=xxx
```


## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\Asana\AsanaChannel;
use NotificationChannels\Asana\AsanaMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    public function via($notifiable)
    {
        return [AsanaChannel::class];
    }

    public function toAsana($notifiable)
    {
        return AsanaMessage::create("Test Task")
            ->notes("Description of task ...")
            ->assignee("john.doe@test.com")
            ->dueOn(new \DateTime());
    }
}
```


### Available methods

- `name('')`: Accepts a string value for the Asana task title.
- `notes('')`: Accepts a string value for the Asana task notes (default: '').
- `assignee('')`: Accepts either email address of an asana user or user id.
- `workspace('')`: Accepts a string value for the Asana workspace (default: config.asana.workspaceId).
- `projects('')`: Accepts a string value for a single Asana project or an array of project ids (default: config.asana.projectId).
- `dueOn('')`: Accepts a string or DateTime object for the Asana task due date.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mauthi@gmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Michael Mauthner](https://github.com/mauthi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
