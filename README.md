# Laravel Persian SMS Notification Channel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/your-vendor-name/laravel-ippanel-notification-channel.svg?style=flat-square)](https://packagist.org/packages/your-vendor-name/laravel-ippanel-notification-channel)
[![Total Downloads](https://img.shields.io/packagist/dl/your-vendor-name/laravel-ippanel-notification-channel.svg?style=flat-square)](https://packagist.org/packages/your-vendor-name/laravel-ippanel-notification-channel)
[![Build Status](https://img.shields.io/github/actions/workflow/status/your-vendor-name/laravel-ippanel-notification-channel/run-tests.yml?branch=main&style=flat-square)](https://github.com/your-vendor-name/laravel-ippanel-notification-channel/actions?query=workflow%3Arun-tests+branch%3Amain)
[![StyleCI](https://styleci.io/repos/YOUR_REPO_ID/shield?branch=main)](https://styleci.io/repos/YOUR_REPO_ID)
[![License](https://img.shields.io/github/license/your-vendor-name/laravel-ippanel-notification-channel.svg?style=flat-square)](https://github.com/your-vendor-name/laravel-ippanel-notification-channel/blob/main/LICENSE)

This package makes it easy to send notifications using various Iranian SMS service providers with Laravel. The first supported provider is [IPPanel](https://ippanel.com/) with Laravel.

## Contents

* [Installation](#installation)
* [Configuration](#configuration)
	* [IPPanel Configuration](#IPPanel)
* [Usage](#usage)
	* [Routing SMS Notifications](#sending-a-simple-text-message)
	* [IPPanel](#ippanel-usage)
		* [Available Message Methods (IPPanel)](#available-message-methods-ippanel)
		* [Sending a Simple Text Message (IPPanel)](#sending-a-simple-text-message-ippanel)
		* [Sending a Pattern-Based Message (IPPanel)](#sending-a-pattern-based-message-ippanel)
		* [Customizing the Sender (IPPanel))](#customizing-the-sender-ippanel)
		* [Scheduling Messages (IPPanel - Note)](#scheduling-messages-ippanel)
		* [Checking Account Credit (IPPanel)](#checking-account-credit-ippanel)
* [Handling Errors](#handling-errors)
* [Testing](#testing)
* [Contributing](#contributing)
* [License](#license)

## Installation
Note: Until this package is officially accepted and published under laravel-notification-channels, please use the direct GitHub repository installation method. 

1- You can install the package via composer:

```bash
composer require laravel-notification-channels/persian-sms
```

2- Install via GitHub (Before publishing to Packagist or for development):
If the package is not yet published on Packagist, or for testing/development purposes, you can install it directly from GitHub.

First, add the repository definition to your project's composer.json file under the repositories section:

```bash
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/saman9074/persian-sms"
    }
],
```
Then, require the package:

```bash
composer saman9074/persian-sms:dev-main
```
## Configuration

You can publish the config file with:

```bash
php artisan vendor:publish --provider="NotificationChannels\PersianSms\PersianSmsServiceProvider" --tag="persian-sms-config"
```

This will create a config/persian-sms.php file in your project. This file allows you to configure the default SMS driver and settings for each supported driver.

```bash
// config/persian-sms.php
return [
    'default_driver' => env('PERSIAN_SMS_DRIVER', 'ippanel'),

    'drivers' => [
        'ippanel' => [
            'api_key'       => env('IPPANEL_API_KEY'),
            'sender_number' => env('IPPANEL_SENDER_NUMBER'),
            // 'api_url'    => 'https://api2.ippanel.com/api/v1', // Optional: if you need to override
        ],
        // ... other drivers like kavenegar will be added here
    ],

    'guzzle' => [
        'timeout' => 10.0,
        // ... other Guzzle options
    ],
];
```
## IPPanel Configuration

Add your IPPanel API Key and default Sender Number to your .env file:

```bash
PERSIAN_SMS_DRIVER=ippanel
IPPANEL_API_KEY=your_ippanel_api_key_here
IPPANEL_SENDER_NUMBER=your_default_ippanel_sender_number_here
```

## Usage

To send notifications, use the NotificationChannels\PersianSms\IPPanel\IPPanelChannel in your notification's via method. You will also need to define a toPersianSms method that returns an NotificationChannels\PersianSms\IPPanel\IPPanelMessage instance.   

```bash
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\PersianSms\IPPanel\IPPanelChannel;
use NotificationChannels\PersianSms\IPPanel\IPPanelMessage;

class OrderShipped extends Notification
{
    use Queueable;

    // ... constructor and other properties

    public function via($notifiable)
    {
        return [IPPanelChannel::class];
    }

    public function toPersianSms($notifiable)
    {
        return (new IPPanelMessage())
                    ->content("Your order has been shipped!");
    }
}
```
Routing SMS Notifications
Your notifiable model (e.g., App\Models\User) needs to implement a method to return the phone number(s) for the notification. The IPPanelChannel will look for these methods in the following order:

1. routeNotificationForPersianSms($notification)
2. routeNotificationFor(IPPanelChannel::class, $notification)
3. routeNotificationForIPPanel($notification)  
4. A phone_number attribute on the notifiable model.  
5. A mobile attribute on the notifiable model.

Example for User model:
```bash
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // ... other model properties

    /**
     * Route notifications for the PersianSms channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string|array|null
     */
    public function routeNotificationForPersianSms($notification)
    {
        return $this->phone_number; // Assuming 'phone_number' is a column in your users table
    }
}
```

Available Message Methods (IPPanel)

The IPPanelMessage class provides a fluent API to construct your message:

    * content(string $text): Sets the content for a normal SMS.
    * pattern(string $patternCode, array $variables = []): Sets the message to be sent using a pattern, including its variables.
    * from(string $senderNumber): Overrides the default sender number for this specific message.
    * at(string $dateTimeString): Sets a scheduled time for sending the SMS (ISO 8601 format like "YYYY-MM-DDTHH:MM:SSZ"). Note: This is prepared in IPPanelMessage but not yet utilized by IPPanelChannel for actual scheduling in the API call.

Sending a Simple Text Message (IPPanel)

In your notification's toPersianSms() method:

```bash
use NotificationChannels\PersianSms\IPPanel\IPPanelMessage;

public function toPersianSms($notifiable)
{
    return (new IPPanelMessage())
                ->content('Your order has been shipped!');
}
```
Then, send the notification from your notifiable model:

```bash
$user->notify(new OrderShipped($order));
```
Sending a Pattern-Based Message (IPPanel)

If you are using IPPanel's pattern-based SMS, use the pattern() method. Pass the pattern code and an associative array of variables.

```bash
use NotificationChannels\PersianSms\IPPanel\IPPanelMessage;

public function toPersianSms($notifiable)
{
    $patternCode = 'your_ippanel_pattern_code'; // Replace with your actual pattern code
    $variables = [
        'name' => $notifiable->name,         // Match variable names with your IPPanel pattern
        'order_id' => $this->order->id,
    ];

    return (new IPPanelMessage())
                ->pattern($patternCode, $variables);
                // Do NOT use ->content() when sending a pattern-based message
}
```
Replace your_ippanel_pattern_code with the code from your IPPanel panel and ensure the keys in the $variables array match the variable names defined in your pattern.

Customizing the Sender (IPPanel)

By default, the channel uses the IPPANEL_SENDER_NUMBER from your config. You can override this for a specific message using the from() method:

```bash
use NotificationChannels\PersianSms\IPPanel\IPPanelMessage;

public function toPersianSms($notifiable)
{
    return (new IPPanelMessage())
                ->content('Message from a custom sender.')
                ->from('your_custom_sender_number'); // e.g., +983000XXXX
}
```
Scheduling Messages (IPPanel - Note)

The IPPanelMessage class has an at(string $dateTimeString) method to specify a future send time. However, the current version of IPPanelChannel.php does not yet pass this scheduled time to the IPPanel API. This feature can be implemented in a future update to the channel.

```bash
use NotificationChannels\PersianSms\IPPanel\IPPanelMessage;

public function toPersianSms($notifiable)
{
    $scheduledTime = "2025-12-31T23:59:59Z"; // Example ISO 8601 string

    return (new IPPanelMessage())
                ->content('This message is intended to be sent later.')
                ->at($scheduledTime); // Note: Currently not implemented in IPPanelChannel API call
}
```

Checking Account Credit (IPPanel)

The IPPanelChannel class provides a getCredit() method to check your IPPanel account balance. You can resolve the channel from the service container and call this method:

```bash
use NotificationChannels\PersianSms\IPPanel\IPPanelChannel;
use NotificationChannels\PersianSms\Exceptions\CouldNotSendNotification;
use Illuminate\Support\Facades\Log;

try {
    $ippanelChannel = app(IPPanelChannel::class);
    $creditData = $ippanelChannel->getCredit();
    Log::info('IPPanel Account Credit:', $creditData);
} catch (CouldNotSendNotification $e) {
    Log::error('Failed to retrieve IPPanel credit: ' . $e->getMessage());
}
```

Note: The exact structure of the returned $creditData array depends on the IPPanel API response for the credit check endpoint.

## Handling Errors

If the IPPanel API returns an error, the channel will throw a NotificationChannels\PersianSms\Exceptions\CouldNotSendNotification exception. You can catch this exception to handle errors gracefully:

```bash
use NotificationChannels\PersianSms\Exceptions\CouldNotSendNotification;
use App\Notifications\OrderShipped;
use App\Models\User;
use Illuminate\Support\Facades\Log;

$user = User::find(1);

try {
    $user->notify(new OrderShipped($order));
} catch (CouldNotSendNotification $e) {
    Log::error('Failed to send IPPanel SMS: ' . $e->getMessage(), [
        'exception_code' => $e->getCode(),
    ]);
}
```
The exception message often contains details from the IPPanel API response.

Available Methods on IPPanelMessage

    * IPPanelMessage::create(string $content = ''): Static factory method to create a simple text message.
    * content(string $content): Set the message content for a normal SMS.
    * pattern(string $patternCode, array $variables = []): Set the message to be sent using a pattern.
    * variable(string $name, $value): (Alternative to passing all variables to pattern()) Set a single variable for a pattern message.
    * from(string $senderNumber): Set a custom sender number for this message.
    * at(string $dateTimeString): Set a scheduled send time (ISO 8601 format). Note: API call implementation pending in channel.
    * isPattern(): bool: Checks if the message is configured as a pattern message.

## Testing

You can run the tests included with the package using:

```bash
composer test
```

## Contributing

Please see CONTRIBUTING for details.

## License

The MIT License (MIT). Please see License File for more information.
