# Notify notifications channel for Laravel 5.6+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/notify.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/notify)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/notify/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/notify)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/notify.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/notify)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/notify/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/notify/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/notify.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/notify)

This package makes it easy to send notifications using [Notify](https://app.notify.eu) with Laravel 5.6+

## Contents

- [Installation](#installation)
	- [Setting up your Notify account](#setting-up-your-notify-account)
- [Usage](#usage)
	- [Available Message methods](#all-available-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

```bash
$ composer require laravel-notification-channels/notify
```

### Setting up your Notify account

Add your ClientId, secret and transport to your `config/services.php`:

NOTIFY_URL is not mandatory. Can be used when you want to overwrite the endpoint Notify is calling. (f.e. different url for Staging/production)

```php
// config/services.php
...
''notify' => [
         'clientID' => env('NOTIFY_CLIENT_ID'),
         'secret' => env('NOTIFY_SECRET'),
         'transport' => env('NOTIFY_TRANSPORT'),
         'url' => env('NOTIFY_URL')
],
...
```

Add your Notify credentials to your `.env`:

```php
// .env
...
NOTIFY_CLIENT_ID=
NOTIFY_SECRET=
NOTIFY_TRANSPORT=
NOTIFY_URL=
],
...
```


## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use App\User;
use Illuminate\Notifications\Notification;
use NotificationChannels\Notify\NotifyChannel;
use NotificationChannels\Notify\NotifyMessage;

class InvoicePaid extends Notification
{
    const TYPE = 'buyerContractApproval';
    protected $user;
    private $cc = [];
    private $bcc = [];


    /**
     * InvoicePaid constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [NotifyChannel::class];
    }

    /**
     * @param $notifiable
     * @return NotifyMessage
     */
    public function toNotify($notifiable)
    {
        return NotifyMessage::create()
            ->setNotificationType(self::TYPE)
            ->setTransport('mail')
            ->setLanguage('en')
            ->setParams($this->getParams())
            ->setCc($this->cc)
            ->setBcc($this->bcc);
    }

    /**
     * @return array
     */
    private function getParams()
    {
        return array('userToken' => $this->user->getRememberToken());
    }

    /**
     * @param array $cc
     * format: array(array('name' => 'John Doe', 'recipient' => 'john@doe.com')
     */
    public function addCc(array $cc)
    {
        $this->cc = $cc;
    }

    /**
     * @param array $bcc
     * format: array(array('name' => 'John Doe', 'recipient' => 'john@doe.com')
     */
    public function addBcc(array $bcc)
    {
        $this->bcc = $bcc;
    }
```

### All available methods

- `notificationType('')`: Accepts a string value.
- `transport('')`: Accepts a string value. if not set, it will fallback to NOTIFY_TRANSPORT in .env file
- `language('')`: Accepts a string value.
- `params($array)`: Accepts an array of key/value parameters
- `Cc($array)`: Accepts an array of arrays of 'name'/'recipient' keys
- `Bcc($array)`: Accepts an array of arrays of 'name'/'recipient' keys

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ composer test
```

## Security

If you discover any security related issues, please email info@notify.eu instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [notify](https://github.com/notify-eu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.