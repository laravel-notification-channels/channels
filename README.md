# Webex Notifications Channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/webex.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/webex)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/github/workflow/status/askmrsinh/channels/PHPUnit%20tests.svg?style=flat-square)](https://github.com/askmrsinh/channels/actions/workflows/tests.yml)
[![StyleCI](https://styleci.io/repos/459859200/shield)](https://styleci.io/repos/459859200)
[![Quality Score](https://img.shields.io/scrutinizer/quality/g/askmrsinh/channels.svg?style=flat-square)](https://scrutinizer-ci.com/g/askmrsinh/channels/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/askmrsinh/channels.svg?style=flat-square)](https://scrutinizer-ci.com/g/askmrsinh/channels/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/webex.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/webex)

This package makes it easy to send notifications using [Webex](https://www.webex.com/) with Laravel
10.x.

```php
    /**
     * Get the Webex Message representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \NotificationChannels\Webex\WebexMessage
     *
     * @throws \NotificationChannels\Webex\Exceptions\CouldNotCreateNotification
     */
    public function toWebex($notifiable)
    {
        return (new WebexMessage)
            ->text('The message, in plain text.')
            ->markdown('# The message, in Markdown format.')
            ->file(function (WebexMessageFile $file) {
                $file->path('https://www.webex.com/content/dam/wbx/global/images/webex-favicon.png');
            })
    }
```

## Contents

- [Installation](#installation)
- [Usage](#usage)
    * [Formatting Webex Notifications](#formatting-webex-notifications)
        + [Plain Text Message](#plain-text-message)
        + [Rich Text Message](#rich-text-message)
        + [User and Group Mentions](#user-and-group-mentions)
        + [Room/Space Linking](#roomspace-linking)
        + [Including a File](#including-a-file)
        + [Including an Attachment](#including-an-attachment)
        + [Replying to a Parent Message](#replying-to-a-parent-message)
    * [Routing Webex Notifications](#routing-webex-notifications)
- [Available Methods](#available-methods)
    * [Webex Message Methods](#webex-message-methods)
    * [Webex Message File Methods](#webex-message-file-methods)
    * [Webex Message Attachment Methods](#webex-message-attachment-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

Install the package using Composer:

```bash
composer require laravel-notification-channels/webex
```

You will also need to include a Webex service configuration to your application. To do this, edit
the `config/services.php` file and add the following:

```php
'webex' => [
    'notification_channel_url' => env('WEBEX_NOTIFICATION_CHANNEL_URL', 'https://webexapis.com/v1/messages'),
    'notification_channel_id' => env('WEBEX_NOTIFICATION_CHANNEL_ID'),
    'notification_channel_token' => env('WEBEX_NOTIFICATION_CHANNEL_TOKEN')
],
```

Then use the `WEBEX_NOTIFICATION_CHANNEL_ID` and `WEBEX_NOTIFICATION_CHANNEL_TOKEN`
[environment variables](https://laravel.com/docs/9.x/configuration#environment-configuration)
to define your Webex ID and Token. One way to get these values is by creating a new
[Webex Bot](https://developer.webex.com/my-apps/new/bot).

## Usage

If you are new to Laravel Notification, I highly recommend reading the official documentation which
goes over the basics
of [generating](https://laravel.com/docs/9.x/notifications#generating-notifications)
and [sending](https://laravel.com/docs/9.x/notifications#sending-notifications) notifications.  
The guide below assumes that you have successfully generated a notification class with a
[`via`](https://laravel.com/docs/9.x/notifications#specifying-delivery-channels)
method whose return value includes `'webex'` or `NotificationChannels\Webex\WebexChannel::class`.
For example:

```php
<?php
 
namespace App\Notifications;
 
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Webex\WebexChannel;
 
class InvoicePaid extends Notification
{
    use Queueable;
 
    // ...
    
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WebexChannel::class];
    }
    
    // ...
}
```

### Formatting Webex Notifications

If a notification supports being sent as a Webex message, you should define a `toWebex`
method on the notification class. This method will receive a `$notifiable` entity and should return
a `\NotificationChannels\Webex\WebexMessage` instance. Webex messages may contain text content 
as well as at most one file or an attachment (for [buttons and cards](https://developer.webex.com/buttons-and-cards-designer)). 

#### Plain Text Message
Let's take a look at a basic `toWebex` example, which we could add to our `InvoicePaid` class
[above](#usage).

```php
    public function toWebex($notifiable)
    {
        return (new WebexMessage)
            ->text('Invoice Paid!');
    }
```

![Preview on (https://web.webex.com)](https://user-images.githubusercontent.com/6129517/154013451-22576d01-6452-41a8-94a9-76afe2ea4fd6.png)

#### Rich Text Message

Send a rich text notification message formatted using the Markdown markup language via
`markdown` method.

```php
    public function toWebex($notifiable)
    {
        return (new WebexMessage)
            ->markdown('# Invoice Paid!');
    }
```

![Preview on (https://web.webex.com)](https://user-images.githubusercontent.com/6129517/154013359-ae5cf5de-a6c1-4c64-bf4c-1008c041e1e6.png)

#### User and Group Mentions

To mention someone in a group room/space use their registered Webex email address or Webex HTTP API
identifier as shown below.

```php
    public function toWebex($notifiable)
    {
        $mentionPersonEmail = 'baburao@example.com';
        
        return (new WebexMessage)
            ->markdown("Hello <@personEmail:$mentionPersonEmail|Babu Bhaiya>! Your invoice is ready for payment.");
    }
```

```php
    public function toWebex($notifiable)
    {
        $mentionPersonId = 'Y2lzY29zcGFyazovL3VzL1BFT1BMRS85OTRmYjAyZS04MWI1LTRmNDItYTllYy1iNzE2OGRlOWUzZjY';

        return (new WebexMessage)
            ->markdown("Hello <@personId:$mentionPersonId|Babu Bhaiya>! Your invoice is ready for payment.");
    }
```

![Preview on (https://web.webex.com)](https://user-images.githubusercontent.com/6129517/154013737-ffb6dd9a-27c8-4463-9f4a-c8935ac8f90a.png)

To mention everyone in a group room/space, use the `<@all>` tag.

```php
    public function toWebex($notifiable)
    {
        return (new WebexMessage)
            ->markdown('Hello <@all>! An invoice is ready for payment.');
    }
```

![Preview on (https://web.webex.com)](https://user-images.githubusercontent.com/6129517/154013869-91edc385-a78f-4cf0-9b49-d8a61b52079d.png)

#### Room/Space Linking

To Link to a room/space use its Webex protocol handler, i.e. `webexteams://im?space=<space_id>`.

```php
    public function toWebex($notifiable)
    {
        return (new WebexMessage)
            ->markdown('We are in the webexteams://im?space=f58064a0-8e21-11ec-9d53-739134f9a8eb space.');
    }
```

![Preview on (https://web.webex.com)](https://user-images.githubusercontent.com/6129517/154014364-eb36a360-1b09-47ac-9a3a-017e93bda31b.png)

#### Including a File

A notification message can have at most one file that you can include via the
`file` helper method.  
When calling the `file` method, you should provide the path of the file. The file path could be
local or of the form "scheme://...", that is accessible to your application. Optionally, you can 
also provide a name and MIME type to display on Webex clients.

```php
    public function toWebex($notifiable)
    {
        $filePath = 'storage/app/invoices/uwnQ0uAXzq.pdf';
        
        return (new WebexMessage)
            ->file(function (WebexMessageFile $file) use ($filePath){
                $file->path($filePath)          // required
                    ->name('invoice.pdf')       // optional
                    ->type('application/pdf');  // optional
            });
    }
```

![Preview on (https://web.webex.com)](https://user-images.githubusercontent.com/6129517/154014597-27eafbc0-01f0-465b-bcb0-cde6ad0d1d30.png)

Note:
 - Including multiple files in the same message is not supported by the `file` helper.
 - Including file and attachment in the same message is not supported by the `file` helper.
 - For supported MIME types and file size limits, please refer Webex HTTP API documentation.

#### Including an Attachment

A notification message can have at most one attachment that you can include via the
`attachment` helper method.  
When calling the `attachment` method, you should provide the content of the attachment. The
attachment content must be a PHP array representation for an
[adaptive card](https://developer.webex.com/buttons-and-cards-designer). Optionally, you can 
also provide a content type.

```php
    public function toWebex($notifiable)
    {
        $invoicePaidCard = json_decode('{
            "type": "AdaptiveCard",
            "version": "1.0",
            "body": [
                {
                    "type": "TextBlock",
                    "text": "Invoice Paid!",
                    "size": "large"
                }
            ],
            "actions": [
                {
                    "type": "Action.OpenUrl",
                    "url": "https://example.com/invoices/uwnQ0uAXzq.pdf",
                    "title": "View Invoice"
                }
            ]
        }');

        return (new WebexMessage)
            ->attachment(function (WebexMessageAttachment $attachment) use ($invoicePaidCard) {
                $attachment->content($invoicePaidCard)                          // required
                    ->contentType('application/vnd.microsoft.card.adaptive');   // optional
            });
    }
```

![Preview on (https://web.webex.com)](https://user-images.githubusercontent.com/6129517/154014764-d9798d94-b924-4cf0-98f9-5273e045f408.png)

Note:
- Including multiple attachments in the same message is not supported by the `attachment` helper.
- Including attachment and file in the same message is not supported by the `attachment` helper.
- For supported attachment types, please refer Webex HTTP API documentation.

#### Replying to a Parent Message

Reply to a parent message and start or advance a thread via the `parentId` method.

```php
    public function toWebex($notifiable)
    {
        $messageId = "Y2lzY29zcGFyazovL3VybjpURUFNOnVzLXdlc3QtMl9yL01FU1NBR0UvNGY0ZGExOTAtOGUyMy0xMWVjLTljZWQtNmZkZWE5MjMxNmNj"

        return (new WebexMessage)
            ->parentId($messageId)
            ->text("Invoice Paid!" . "\n"
                   "No Further action required at this time");
    }
```

![image](https://user-images.githubusercontent.com/6129517/154110938-ddf32f57-c0bd-4e82-85ef-6402a232e1ed.png)


### Routing Webex Notifications

To route Webex notifications to the proper Webex user or room/space, define a
`routeNotificationForWebex` method on your notifiable entity. This should return a Webex registered
email address or a Webex HTTP API resource identifier for a user or room/space to which the
notification should be delivered.

```php
<?php
 
namespace App\Models;
 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
 
class User extends Authenticatable
{
    use Notifiable;
 
    /**
     * Route notifications for the Webex channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForWebex($notification)
    {
        if (!empty($this->email)) {             // a Webex registered email address
            return $this->email;
        } else if (!empty($this->personId)) {   // a Webex HTTP API resource identifier for a user
            return $this->personId;
        } else if (!empty($this->roomId)) {     // a Webex HTTP API resource identifier for a room/space
            return $this->roomId;
        }

        return null;                            // don't route notification for Webex channel
    }
}
```

## Available Methods

The package consists of three Webex messaging classes:
- `\NotificationChannels\Webex\WebexMessage` 
- `\NotificationChannels\Webex\WebexMessageFile`
- `\NotificationChannels\Webex\WebexMessageAttachment`

All three of them implement a `toArray` method that returns the instance as an array suitable 
for `multipart/form-data` request.
Additionally, the `WebexMessage`and `WebexMessageAttachment`classes also implement `jsonSerialize` 
and `toJson` methods. The `jsonSerialize` method returns the instance as an array suitable for 
`application/json` request or `json_encode`, while the `toJson` method returns the instance as 
a JSON string.
All three of these methods are used internally for creating the request payload to Webex HTTP API.

### Webex Message Methods

Public methods of the `\NotificationChannels\Webex\WebexMessage` class:

- `text(string $content)`: Set the content of the message, in plain text.
- `markdown(string $content)`: Set the content of the message, in Markdown format.
- `parentId(string $id)`: Set the parent message to reply to.
- `to(string $recipient)`: Set the recipient of the message.
- `file(Closure $callback)`: Set a file to include in the message.
- `attachment(Closure $callback)`: Set an attachment to include in the message.

### Webex Message File Methods

Public methods of the `\NotificationChannels\Webex\WebexMessageFile` class:

- `path(string $path)`: Set the path for the file.
- `name(string $name)`: Set the user provided name for the file.
- `type(string $type)`: Set the user provided MIME type for the file.

### Webex Message Attachment Methods

Public methods of the `\NotificationChannels\Webex\WebexMessageAttachment` class:

- `contentType(string $contentType)`: Set the content type of the attachment.
- `content($content)`: Set the content of the attachment.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email ask@mrsinh.com instead of using the issue
tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Ashesh Singh](https://github.com/askmrsinh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
