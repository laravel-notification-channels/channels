## Signal notification channel for Laravel

Easily send end-to-end encrypted notifications for Laravel using [Signal](https://signal.org).

## Contents

- [Installation](#installation)
	- [Setting up the Signal service](#setting-up-the-Signal-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation
1) Set up signal-cli

This package requires [`signal-cli`](https://github.com/AsamK/signal-cli) to communicate with the Signal service. Precompiled binaries are available [here](https://github.com/AsamK/signal-cli/releases/latest).

Extract the binary file to a directory of your choice. Signal-cli requires JRE 7 or newer.

2) Set your signal-cli and JAVA_HOME paths in SignalChannel.

3) Register your phone number (username) with the Signal service:
``` bash
./signal-cli --username +12345556789 register
```

Add your phone number (username) to `.env`:
```dotenv
SIGNAL_USERNAME="+12345556789" # Prefix ("+") and country code are required.
```

## Usage

```//...
use NotificationChannels\Signal\SignalChannel;
use NotificationChannels\Signal\SignalMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification
{
	use Queueable;

	public function via($notifiable)
	{
		return [SignalChannel::class];
	}

	public function toSignal($notifiable)
	{
		return (new SignalMessage())
			->message("This is a test Laravel notification message over Signal.")
			->recipient("+12345556789");
	}
```

Notifications will be sent to the `recipient` attribute of the Notifiable model.

### Available Message methods

`message 'string'`

`recipient 'string'`

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email cjbarlow@protonmail.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [CJ Barlow](https://github.com/tehCh0nG)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
