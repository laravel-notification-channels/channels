<?php

namespace NotificationChannels\GoogleChat\Tests\Fixtures;

use Illuminate\Notifications\Notification;
use NotificationChannels\GoogleChat\Card;
use NotificationChannels\GoogleChat\Components\Button\ImageButton;
use NotificationChannels\GoogleChat\Components\Button\TextButton;
use NotificationChannels\GoogleChat\GoogleChatChannel;
use NotificationChannels\GoogleChat\GoogleChatMessage;
use NotificationChannels\GoogleChat\Section;
use NotificationChannels\GoogleChat\Widgets\Buttons;
use NotificationChannels\GoogleChat\Widgets\Image;
use NotificationChannels\GoogleChat\Widgets\KeyValue;
use NotificationChannels\GoogleChat\Widgets\TextParagraph;

class TestEndToEndNotification extends Notification
{
    public function via($notifiable)
    {
        return [GoogleChatChannel::class];
    }

    public function toGoogleChat($notifiable)
    {
        $message = GoogleChatMessage::create()
            ->text('This is a test end-to-end notification.')
            ->card([
                Card::create([
                    Section::create(
                        TextParagraph::create('Simple paragraph text')
                    ),
                    Section::create()
                        ->widget(
                            KeyValue::create(
                                'Top Label',
                                'Content',
                                'Bottom Label'
                            )
                            ->icon('TRAIN')
                            ->onClick('https://example.com/key-value-click')
                            ->setContentMultiline(true)
                            ->button(
                                ImageButton::create('https://example.com/key-value-button-click', 'https://picsum.photos/64/64')
                            )
                        )
                        ->widget(
                            Image::create('https://picsum.photos/300/150', 'https://example.com/img-clickthrough')
                        ),
                ])->header(
                    'First Card',
                    'First Card - Subtitle',
                    'https://picsum.photos/65/65',
                    'IMAGE'
                ),
                Card::create(
                    Section::create(
                        Buttons::create(
                            TextButton::create('https://example.com/card-2-cta', 'Go There')
                        )
                    )
                )->header(
                    'Second Card',
                    'Second Card - Subtitle',
                    'https://picsum.photos/66/66',
                    'AVATAR'
                ),
            ]);

        return $message;
    }
}
