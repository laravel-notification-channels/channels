Here's the latest documentation on Laravel Notifications System: 

https://laravel.com/docs/master/notifications

# A Boilerplate repo for contributions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/sailthru.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/sailthru)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/sailthru/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/sailthru)
[![StyleCI](https://styleci.io/repos/:style_ci_id/shield)](https://styleci.io/repos/:style_ci_id)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/sailthru.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/sailthru)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/sailthru/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/sailthru/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/sailthru.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/sailthru)

This package makes it easy to send notifications using [Sailthru](https://www.sailthru.com) with Laravel 5.8.

**Note:** Replace ```Sailthru``` ```Sailthru``` ```Dylan Harbour``` ```dylanharbour``` ```http://ringier.tech``` ```tools@roam.africa``` ```sailthru``` ```:package_description``` ```:style_ci_id``` ```:sensio_labs_id``` with their correct values in [README.md](README.md), [CHANGELOG.md](CHANGELOG.md), [CONTRIBUTING.md](CONTRIBUTING.md), [LICENSE.md](LICENSE.md), [composer.json](composer.json) and other files, then delete this line.
**Tip:** Use "Find in Path/Files" in your code editor to find these keywords within the package directory and replace all occurences with your specified term.

This is where your description should go. Add a little code example so build can understand real quick how the package can be used. Try and limit it to a paragraph or two.



## Contents

- [Installation](#installation)
	- [Setting up the Sailthru service](#setting-up-the-Sailthru-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

1. Add your Sailthru API details to `config/services`
```
'sailthru' => [
        'api_key' => '',
        'secret' => '',
    ],
```
2. `composer require laravel-notification-channels/sailthru-notifications-channel`
(Specify Repo until official package is added to the notifications channel repo)

2. If required, register the `SailthruServiceProvider`


### Setting up the Sailthru service

Sign Up on their Website

## Usage

1. Follow standard Notifiable/Notifications Setup for a model. 
2. If default / global vars are required for all Sailthru calls, add them using the `sailthruDefaultVars` method on your notifiable e.g. 
```
    /**
     * @return array
     */
    public function sailthruDefaultVars(): array
    {
        return ['global_var_foo' => 'bar'];

    }
```
3. Add a `toSailthru` method on your notification, where you define template name and any specific var. 

```
    /**
     * @param User $user
     * @return SailthruMessage
     */
    public function toSailthru(User $user)
    {

        return SailthruMessage::create('reset password')
            ->toName($user->name)
            ->toEmail($user->email)
            ->vars(
                [
                    'reset_password_link' => 'https://www.example.com',
                    'link_expiration_minutes' => config('auth.reminder.expire', 60),
                    'first_name' => $user->name,
                ]
            );
    }
```

4. Change the `via()` method in your notification to include the `SailthruChannel`.

```
    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return [SailthruChannel::class];
    }

```
### Available Message methods

A list of all available options

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email tools@roam.africa instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- Developed and Maintained by [Ringier Tech & Data](http://ringier.tech) and [Ringier One Africa Media](https://roam.africa)
- [Dylan Harbour](https://github.com/dylanharbour)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
