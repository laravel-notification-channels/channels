# WeChat Notifications Channel for Laravel

This package makes it easy to send [WeChat](https://www.wechat.com) notifications with Laravel 5.3+

> This package is dependency on [overtrue/laravel-wechat](https://github.com/overtrue/laravel-wechat) package.

## Contents

- [Installation](#installation)
- [Usage](#usage)
    - [Sending a simple wechat notification](#sending-a-simple-wechat-notification)
    - [Available methods](#available-methods)
- [Testing](#testing)
- [Security](#security)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/wechat
```


## Usage

### Sending a simple wechat notification

``` php
use NotificationChannels\WeChat\WeChatChannel;
use NotificationChannels\WeChat\WeChatMessage;
use Illuminate\Notifications\Notification;

class UserRegistered extends Notification
{
    public function via($notifiable)
    {
        return [WeChatChannel::class];
    }

    public function toWeChat($notifiable)
    {
        return (new WeChatMessage)
                ->to('wechat-user-openid')
                ->uses('template-id')
                ->andUrl('http://example-url.com')
                ->data([
                    'first' => 'some-param',
                    'keyword1' => 'some-param',
                    'keyword2' => 'some-param',
                ]);
    }
}
```



### Available methods

#### `WeChatMessage`

- You can see the methods in [EasyWeChat Template Message Document](https://easywechat.org/en/docs/notice.html#Chained-Method-usage)


## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email mingyoungcheung@gmail.com instead of using the issue tracker.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mingyoung Cheung](https://github.com/mingyoung)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.