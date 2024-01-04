Please see [this repo](https://github.com/laravel-notification-channels/channels) for instructions on how to submit a channel proposal.

# Ntfy

This package makes it easy to send notifications using [ntfy](https://ntfy.sh/) with Laravel 5.5+, 6.x and 7.x

## Contents

- [Installation](#installation)
    - [Setting up the ntfy service](#setting-up-the-ntfy-service)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
- [Testing](#testing)
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

Configure ntft service in `config/boardcasting.php`:

```php
 'ntfy' => [
            'driver' => 'ntfy',
            'host' => env('NTFY_HOST'),
            'port' => env('NTFY_PORT'),
            'username' => env('NTFY_USERNAME'),
            'password' => env('NTFY_PASSWORD'),
        ],
```
## Usage

Now you can use the channel in your `via()` method inside the notification:

```php
 public function via(object $notifiable): array
    {
        return [NtfyChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toNtfy(object $notifiable)
    {
       return (new NtfyMessage())
           ->topic('test')
            ->title('Test Notification')
            ->body('This is a test notification from Ntfy');
    }
```

### Available Message methods

- `topic('')`: Accepts a string value for the topic of the notification.
- `title('')`: Accepts a string value for the title of the notification.
- `body('')`: Accepts a string value for the body of the notification.
- `priority('')`: Accepts a string value for the priority of the notification.(1=Min priority, 2=Low priority, 3=Default priority, 4=High priority, 5=Max priority)
- 


## Testing

``` bash
$ composer test
```

## Credits

- [Cengiz Onkal](https://github.com/cengizonkal)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
