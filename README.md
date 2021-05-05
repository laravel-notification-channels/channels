<p align="center">
<img src="laravel-notifications+google-chat.png" width="450">
</p>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/google-chat.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/google-chat)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/laravel-notification-channels/google-chat/run-tests?label=tests)](https://github.com/laravel-notification-channels/google-chat/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/laravel-notification-channels/google-chat/Check%20&%20fix%20styling?label=code%20style)](https://github.com/laravel-notification-channels/google-chat/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/google-chat.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/google-chat)

<h1>Google Chat - Laravel Notification Channel</h1>

This package makes it easy to send notifications using [Google Chat](https://developers.google.com/hangouts/chat) , (formerly known as Hangouts Chat) with Laravel 8.x

````php
class InvoicePaidNotification extends Notification
{
    // Create a super simple message
    public function toGoogleChat($notifiable)
    {
        return GoogleChatMessage::create('An invoice was paid!');
    }

    // ...Or you can use some simple formatting
    public function toGoogleChat($notifiable)
    {
        return GoogleChatMessage::create()
            ->text('Someone just paid an invoice... ')
            ->bold('Woo-hoo!')
            ->line('Looking for ')
            ->link(route('invoices'), 'the details?')
            ->to('sales_team'); // ... and route it to specific rooms
    }

    // ...Or, you can be **extra** and go full-fancy
    public function toGoogleChat($notifiable)
    {
        return GoogleChatMessage::create()
            ->text('Invoice Paid! Here\'s the deets:')
            ->card(Card::create(
                Section::create(
                    KeyValue::create('Amount', '$520.99', '#10004756')
                        ->onClick(route('invoices'))
                        ->button(TextButton::create(route('invoices'), 'View'))
                )
            ))
    }
}
````


## Contents

- [Installation](#installation)
	- [Generating a Webhook](#generating-a-webhook)
- [Configuring & Using Webhooks in Your Application](#configuring-&-using-webhooks-in-your-application)
	- [Alternate Rooms](#alternate-rooms)
	- [Explicit Webhook Routing](#explicit-webhook-routing)
- [Usage](#usage)
	- [Simple Messages](#simple-messages)
	- [Card Messages](#card-messages)
- [API Overview](#api-overview)
	- [NotificationChannels\GoogleChat\GoogleChatMessage](#notificationchannelsgooglechatgooglechatmessage)
	- [NotificationChannels\GoogleChat\Card](#notificationchannelsgooglechatcard)
	- [NotificationChannels\GoogleChat\Section](#notificationchannelsgooglechatsection)
	- [NotificationChannels\GoogleChat\Widgets\TextParagraph](#notificationchannelsgooglechatwidgetstextparagraph)
	- [NotificationChannels\GoogleChat\Widgets\KeyValue](#notificationchannelsgooglechatwidgetskeyvalue)
	- [NotificationChannels\GoogleChat\Widgets\Image](#notificationchannelsgooglechatwidgetsimage)
	- [NotificationChannels\GoogleChat\Widgets\Buttons](#notificationchannelsgooglechatwidgetsbuttons)
	- [NotificationChannels\GoogleChat\Components\Button\TextButton](#notificationchannelsgooglechatcomponentsbuttontextbutton)
	- [NotificationChannels\GoogleChat\Components\Button\ImageButton](#notificationchannelsgooglechatcomponentsbuttonimagebutton)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)


## Installation

The Google Chat notification channel can be installed easily via Composer:

````bash
$ composer require laravel-notification-channels/google-chat
````

### Generating a Webhook

This package makes use of Google Chat's convenient 'Webhooks' feature, which allows for quick and easy setup.

You can learn how to create a room, and generate a new Webhook on [Google's documentation](https://developers.google.com/hangouts/chat/how-tos/webhooks).

In short, you'll need to:
1. Create a 'room'
2. Visit the 'Manage Webhooks' settings pane for that room
3. Create and configure a new Webhook

You can create a webhook in the various rooms you have access to, in order to better organize your messages. For instances, you might create a 'Sales Alert' webhook in your 'Sales' room, which includes team members who wish to be notified about sales events in your app. Likewise, you can create a 'DevOps' room which includes technical team members wanting to be notified when something technical happens in your app, and create a separate 'Dev Alerts' webhook in there.

Next, we'll look at how we can use the webhook(s) in the Google Chat notification channel

> Through Google's own documentation, the term 'Space' is used to reference conversations generally across Google Chat. A user can be involved in a one-to-one conversation with a co-worker or another bot, or be a member of a 'room' containing many people/bots.
> 
> Although this package only has the ability to send messages into 'rooms', **we still refer to a room as a 'space' for consistency** between this package's documentation, and Google's.

## Configuring & Using Webhooks in Your Application

Firstly, let's publish the configuration file used by the Google Chat notification channel into your application, so that we can start configuring our webhooks:

````bash
$ php artisan vendor:publish --tag google-chat-config
````

Next, if we only have a single room / webhook we want to post into, we can simply configure the `space` key which defines the default conversation notifications sent via the Google Chat channel will be posted to.

````php
// config/google-chat.php

return [
    'space' => 'https://chat.googleapis.com/room-webhook-for-all-notifications?key=xxxxx'
]
````

Notifications that have not otherwise been directed to another room will now be sent to this default space.

**CAUTION!** If your application sends sensitive notifications via Google Chat, we recommend you configure the `space` key to `NULL`, so that notifications must be explicitly directed to an endpoint.

### Alternate Rooms

You can also define alternate webhooks under the `spaces` (plural) key, and reference these more easily throughout your application:

````php
// config/google-chat.php

return [
    'spaces' => [
        'sales_team' => 'https://chat.googleapis.com/sales-team-room?key=xxxxx',
        'dev_team' => 'https://chat.googleapis.com/dev-team-room?key=xxxxx',
        'executive' => 'https://chat.googleapis.com/executives?key=xxxxx',
    ]
]
````

You can now direct notifications to one of these configured rooms by using the relevant key anywhere you can route notifications, like:

````php
Notification::route('googleChat', 'sales_team')->...

// Or

GoogleChatMessage::create()->to('dev_team')->...

// Or, even in your notifiable class:
public function routeNotificationForGoogleChat()
{
    return 'executive';
}
````

### Explicit Webhook Routing

If needed, you can route notifications to explicit webhooks in all the same places listed above. This isn't the preferred method, however, as it gives you less flexibility should you ever need to change a webhook.

````php
Notification::route('googleChat', 'https://chat.googleapis.com/xxxxx')->...

// Or

GoogleChatMessage::create()->to('https://chat.googleapis.com/xxxxx')->...

// Or, even in your notifiable class:
public function routeNotificationForGoogleChat()
{
    return 'https://chat.googleapis.com/xxxxx';
}
````

## Usage

In order to send a notification via the Google Chat channel, you'll need to specify the channel in the `via()` method of your notification:

````php
use NotificationChannels\GoogleChat\GoogleChatChannel;

// ...

public function via($notifiable)
{
    return [
        GoogleChatChannel::class
    ]
}
````

> If you haven't already, make sure you understand [how notification classes are constructed](https://laravel.com/docs/8.x/notifications).

Google Chat messages have [two formats](https://developers.google.com/hangouts/chat/reference/message-formats): Simple Messages, and Card Messages. This package allows you to construct both.

### Simple Messages

Simple messages can be created easily using a `NotificationChannels\GoogleChat\GoogleChatMessage` instance directly, and returned in your notification class like so:

````php
use NotificationChannels\GoogleChat\GoogleChatMessage;

public function toGoogleChat($notifiable)
{
    return GoogleChatMessage::create('Hello world!');
}
````

Simple messages can also contain basic formatting:

````php
use NotificationChannels\GoogleChat\GoogleChatMessage;

public function toGoogleChat($notifiable)
{
    return GoogleChatMessage::create()
        ->bold('Hey Awesome Team Member!')
        ->line('This is a daily reminder that you\'re ')
        ->italic('awesome. ')
        ->text('I mean like ')
        ->link('https://giphy.com/search/you-are-awesome', 'really awesome.');
}
````

### Card Messages

Google Chat cards are more complex pieces of UI that can display organized information to the recipient. Cards are added to a `GoogleChatMessage` instance, and can be used in combination with a simple message.

The structure of cards in this package closely resembles the actual data format sent to the webhook endpoint. For this reason, it's worth checking out [how cards are structured](https://developers.google.com/hangouts/chat/reference/message-formats/cards).

````php
use NotificationChannels\GoogleChat\GoogleChatMessage;
use NotificationChannels\GoogleChat\Card;
use NotificationChannels\GoogleChat\Section;
use NotificationChannels\GoogleChat\Widgets\KeyValue;
use NotificationChannels\GoogleChat\Enums\Icon;
use NotificationChannels\GoogleChat\Enums\ImageStyle;

public function toGoogleChat($notifiable)
{
    return GoogleChatMessage::create()
        ->text('An invoice was just paid... ')
        ->bold('Woo-hoo!')
        ->card(
            Card::create()
                ->header(
                    'Invoice Paid',
                    '#1004756',
                    'https://cdn.example.com/avatars/xxx.png',
                    ImageStyle::CIRCULAR
                )
                ->section(
                    Section::create(
                        KeyValue::create(
                            'Payment Received',
                            '$20.14',
                            'Paid by Josephine Smith'
                        )
                        ->icon(Icon::DOLLAR)
                        ->onClick(route('invoice.show'))
                    )
                )
        )
}
````

Card messages have the following structure

````
cards
|
|---card
|   |
|   |---header
|   |
|   |---sections
|   |   |
|   |   |---section
|   |   |   |
|   |   |   |---widgets
|   |   |   |   |
|   |   |   |   |---widget
|   |   |   |   |
|   |   |   |   |---widget
|   |   |
|   |   |---section
|   |   |   ...
|
|---card
|   ...
````

## API Overview

### NotificationChannels\GoogleChat\GoogleChatMessage

The `GoogleChatMessage` class encompasses an entire message that will be sent to the Google Chat room.

- `static create(?string $text)` Instantiates and returns a new `GoogleChatMessage` instance, optionally pre-configuring it with the provided simple text
- `to(string $space)` Specifies the webhook or space key this notification will be sent to. This takes precedence over the default space and any value returned by a notifiable's `routeNotificationForGoogleChat()` method
- `text(string $message)` Appends `$message` to the simple message content
- `line(string $message)` Appends `$message` on a new line
- `bold(string $message)` Appends bold text
- `italic(string $message)` Appends italic text
- `strikethrough(string $message)` Appends strikethorugh text
- `strike(string $message)` Alias for `strikethrough()`
- `monospace(string $message)` Appends monospace text
- `mono(string $message)` Alias for `monospace()`
- `monospaceBlock(string $message)` Appends a block of monospace text
- `link(string $link, ?string $displayText)` Appends a text link, optionally with custom display text
- `mention(string $userId)` Appends a mention text targeting a specific user id
- `mentionAll(?string $prependText, ?string $appendText)` Appends a mention-all text, optionally with text before and after the block
- `card(Card|Card[] $card)` Add one or more complex card UIs to the message

### NotificationChannels\GoogleChat\Card

The `Card` class represents the top level layout definition for a Card UI to be sent in a message. Cards define one or more sections, and may optionally define header information

- `static create(Section|Section[]|null $section)` Instantiates and returns a new `Card` instance, optionally pre-configuring it with the provided section or sections
- `header(string $title, ?string $subtitle, ?string $imageUrl, ?string $imageStyle)` Optional - Configures the header UI for the card. Note that `$imageStyle` is one of the constants defined in `NotificationChannels\GoogleChat\Enums\ImageStyle`
- `section(Section|Section[] $section)` Add one or more sections to the card

### NotificationChannels\GoogleChat\Section

The `Section` class defines the intermediary layout definition of a card. From a UI perspective, it groups related widgets.

- `static create(AbstractWidget|AbstractWidget[]|null $widgets)` Instantiates and returns a new `Section` instance, optionally pre-configuring it with the provided widget or widgets
- `header(string $text)` Optionally defines the simple header displayed at the top of the section
- `widget(AbstractWidget|AbstractWidgets[] $widget)` Adds one or more widgets to the section

### NotificationChannels\GoogleChat\Widgets\TextParagraph

The `TextParagraph` widget defines rich text. This widget can define more complex text formats than permissible in a simple message.

- `static create(?string $message)` Instantiates and returns a new `TextParagraph` instance, optionally pre-configuring it with the provided text
- `text(string $message)` Appends the `$message` to the widget content
- `bold(string $message)` Appends bold text
- `italic(string $message)` Appends italic text
- `underline(string $message)` Appends underline text
- `strikethrough(string $message)` Appends strikethrough text
- `strike(string $message)` Alias for `strikethrough()`
- `color(string $message, string $hex)` Appends colored text according to the `$hex` color
- `link(string $link, ?string $displayText)` Appends a textual link, optionally with the provided display text
- `break()` Appends a line break

### NotificationChannels\GoogleChat\Widgets\KeyValue

The `KeyValue` widget defines a table like element that can segment information and provide an external click through

- `static create(?string $topLabel, ?string $content, ?string $bottomLabel)` Instantiates and returns a new `KeyValue` instance, optionally pre-configuring it with a top label, content and bottom label.
- `topLabel(string $message)` Defines the top label text
- `content(string $content)` Defines the primary text content of the widget
- `bottomLabel(string $message)` Defines the bottom label text
- `setContentMultiline(bool $value)` Determines whether the primary content should flow onto multiple lines. Google defaults this value to `false`
- `onClick(string $url)` Defines a click through URL which can be activated by clicking the widget itself. Note that this is a different definition from the button, which may optionally be added to the widget too.
- `icon(string $icon)` Defines the glyph icon displayed with the text content; One of the constants defined in `NotificationChannels\GoogleChat\Enums\Icon`
- `button(AbstractButton $button)` Optionally defines a button displayed alongside the text content

### NotificationChannels\GoogleChat\Widgets\Image

The `Image` widget defines a simple image to be displayed in the card. Optionally, a click through URL can be configured for when a user clicks/taps on the image.

- `static create(?string $imageUrl, ?string $onClickUrl)` Instantiates and returns a new `Image` instance, optionally pre-configuring it with an image URL and click through URL.
- `imageUrl(string $url)` Defines the image URL where the image can be sourced
- `onClick(string $url)` Defines a URL the user will be taken to if they click/tap on the image

### NotificationChannels\GoogleChat\Widgets\Buttons

The `Buttons` widget acts as a container for one or more buttons, laid out horizontally. This widget accepts instances of `NotificationChannels\GoogleChat\Components\Button\AbstractButton` and can accept buttons of different types.

- `static create(AbstractButton|AbstractButton[]|null $buttons)` Instantiates and returns a new `Buttons` instance, optionally pre-configuring it with the provided buttons
- `button(AbstractButton|AbstractButton[] $button)` Adds one or more buttons

### NotificationChannels\GoogleChat\Components\Button\TextButton

The `TextButton` defines a simple text button, and can be accepted anywhere that an `AbstractButton` is accepted.

- `static create(?string $url, ?string $displayText)` Instantiates and returns a new `TextButton` instance, optionally pre-configuring it with the provided URL and display text
- `url(string $url)` Defines the target endpoint for the button
- `text(string $text)` Defines the display text for the button

### NotificationChannels\GoogleChat\Components\Button\ImageButton

The `ImageButton` defines a clickable icon or image, and can be accepted anywhere that an `AbstractButton` is accepted. The icon can either be a default icon (one of the constants defined in `NotificationChannels\GoogleChat\Enums\Icon`) or an external image url.

- `static create(?string $url, ?string $icon)` Instantiates and returns a new `ImageButton` instance, optionally pre-configuring it with the provided URL and icon
- `url(string $url)` Defines the target endpoint for the button
- `icon(string $icon)` Defines the icon or image to display for the button.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email frank@thetreehouse.family instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Frank Dixon](https://github.com/frankieeedeee)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
