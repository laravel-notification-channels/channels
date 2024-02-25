<?php

namespace NotificationChannels\Webex\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery as m;
use Mockery\MockInterface;
use NotificationChannels\Webex\WebexChannel;
use NotificationChannels\Webex\WebexMessage;
use NotificationChannels\Webex\WebexMessageAttachment;
use NotificationChannels\Webex\WebexMessageFile;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for WebexChannel.
 */
class WebexChannelTest extends TestCase
{
    /**
     * @var MockInterface|Client
     */
    private $guzzleHttp;

    /**
     * @var WebexChannel
     */
    private $webexChannel;

    /**
     * Checks and transforms a stream resource into a string, in-place.
     *
     * @param  resource|mixed  $item  some value
     * @return void
     */
    protected static function getResourceContents(&$item)
    {
        if (is_resource($item)) {
            $item = stream_get_contents($item);
        }
    }

    /**
     * @dataProvider payloadDataProvider
     *
     * @param  Notification  $notification
     * @param  array  $payload
     */
    public function testCorrectPayloadIsSentToWebex(Notification $notification, array $payload)
    {
        $this->guzzleHttp->shouldReceive('request')
            ->andReturnUsing(function ($argMethod, $argUri, $argOptions) use ($payload) {
                $this->assertEquals('url', $argUri);
                array_walk_recursive($payload, [$this, 'getResourceContents']);
                array_walk_recursive($argOptions, [$this, 'getResourceContents']);
                $this->assertEquals($payload, $argOptions);

                return new Response();
            });

        $this->webexChannel->send(new WebexChannelTestNotifiable, $notification);
    }

    public static function payloadDataProvider(): array
    {
        return [
            'payload_with_text' => static::getPayloadWithText(),
            'payload_with_markdown' => static::getPayloadWithMarkdown(),
            'payload_with_file' => static::getPayloadWithFile(),
            'payload_with_attachment' => static::getPayloadWithAttachment(),
            'payload_with_text_markdown' => static::getPayloadWithTextMarkdown(),
            'payload_with_text_file' => static::getPayloadWithTextFile(),
            'payload_with_text_attachment' => static::getPayloadWithTextAttachment(),
            'payload_with_markdown_file' => static::getPayloadWithMarkdownFile(),
            'payload_with_markdown_attachment' => static::getPayloadWithMarkdownAttachment(),
            'payload_with_text_markdown_file' => static::getPayloadWithTextMarkdownFile(),
            'payload_with_text_markdown_attachment' => static::getPayloadWithTextMarkdownAttachment(),
        ];
    }

    private static function getPayloadWithText(): array
    {
        return [
            new WebexChannelTextTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'json' => [
                    'toPersonEmail' => 'email@example.com',
                    'text' => 'The message, in plain text.',
                ],
            ],
        ];
    }

    private static function getPayloadWithMarkdown(): array
    {
        return [
            new WebexChannelMarkdownTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'json' => [
                    'toPersonEmail' => 'email@example.com',
                    'markdown' => '# The message, in Markdown format.',
                ],
            ],
        ];
    }

    private static function getPayloadWithFile(): array
    {
        return [
            new WebexChannelFileTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'multipart' => [
                    [
                        'name' => 'toPersonEmail',
                        'contents' => 'email@example.com',
                    ],
                    [
                        'name' => 'files',
                        'contents' => Utils::tryFopen(__DIR__.'/fixtures/file.txt', 'r'),
                    ],
                ],
            ],
        ];
    }

    public static function getPayloadWithAttachment(): array
    {
        return [
            new WebexChannelAttachmentTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'json' => [
                    'toPersonEmail' => 'email@example.com',
                    'text' => '',
                    'attachments' => [
                        [
                            'contentType' => 'application/vnd.microsoft.card.adaptive',
                            'content' => [
                                'type' => 'AdaptiveCard',
                                'version' => '1.0',
                                'body' => [
                                    [
                                        'type' => 'TextBlock',
                                        'text' => 'Adaptive Cards',
                                        'size' => 'large',
                                    ],
                                ],
                                'actions' => [
                                    [
                                        'type' => 'Action.OpenUrl',
                                        'url' => 'https://adaptivecards.io',
                                        'title' => 'Learn More',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function getPayloadWithTextMarkdown(): array
    {
        return [
            new WebexChannelTextMarkdownTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'json' => [
                    'toPersonEmail' => 'email@example.com',
                    'text' => 'The message, in plain text.',
                    'markdown' => '# The message, in Markdown format.',
                ],
            ],
        ];
    }

    private static function getPayloadWithTextFile(): array
    {
        return [
            new WebexChannelTextFileTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'multipart' => [
                    [
                        'name' => 'toPersonEmail',
                        'contents' => 'email@example.com',
                    ],
                    [
                        'name' => 'text',
                        'contents' => 'The message, in plain text.',
                    ],
                    [
                        'name' => 'files',
                        'contents' => Utils::tryFopen(__DIR__.'/fixtures/file.txt', 'r'),
                    ],
                ],
            ],
        ];
    }

    private static function getPayloadWithTextAttachment(): array
    {
        return [
            new WebexChannelTextAttachmentTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'json' => [
                    'toPersonEmail' => 'email@example.com',
                    'text' => 'The message, in plain text.',
                    'attachments' => [
                        [
                            'contentType' => 'application/vnd.microsoft.card.adaptive',
                            'content' => [
                                'type' => 'AdaptiveCard',
                                'version' => '1.0',
                                'body' => [
                                    [
                                        'type' => 'TextBlock',
                                        'text' => 'Adaptive Cards',
                                        'size' => 'large',
                                    ],
                                ],
                                'actions' => [
                                    [
                                        'type' => 'Action.OpenUrl',
                                        'url' => 'https://adaptivecards.io',
                                        'title' => 'Learn More',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function getPayloadWithMarkdownFile(): array
    {
        return [
            new WebexChannelMarkdownFileTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'multipart' => [
                    [
                        'name' => 'toPersonEmail',
                        'contents' => 'email@example.com',
                    ],
                    [
                        'name' => 'markdown',
                        'contents' => '# The message, in Markdown format.',
                    ],
                    [
                        'name' => 'files',
                        'contents' => Utils::tryFopen(__DIR__.'/fixtures/file.txt', 'r'),
                    ],
                ],
            ],
        ];
    }

    public static function getPayloadWithMarkdownAttachment(): array
    {
        return [
            new WebexChannelMarkdownAttachmentTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'json' => [
                    'toPersonEmail' => 'email@example.com',
                    'markdown' => '# The message, in Markdown format.',
                    'attachments' => [
                        [
                            'contentType' => 'application/vnd.microsoft.card.adaptive',
                            'content' => [
                                'type' => 'AdaptiveCard',
                                'version' => '1.0',
                                'body' => [
                                    [
                                        'type' => 'TextBlock',
                                        'text' => 'Adaptive Cards',
                                        'size' => 'large',
                                    ],
                                ],
                                'actions' => [
                                    [
                                        'type' => 'Action.OpenUrl',
                                        'url' => 'https://adaptivecards.io',
                                        'title' => 'Learn More',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function getPayloadWithTextMarkdownFile(): array
    {
        return [
            new WebexChannelTextMarkdownFileTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'multipart' => [
                    [
                        'name' => 'toPersonEmail',
                        'contents' => 'email@example.com',
                    ],
                    [
                        'name' => 'text',
                        'contents' => 'The message, in plain text.',
                    ],
                    [
                        'name' => 'markdown',
                        'contents' => '# The message, in Markdown format.',
                    ],
                    [
                        'name' => 'files',
                        'contents' => Utils::tryFopen(__DIR__.'/fixtures/file.txt', 'r'),
                    ],
                ],
            ],
        ];
    }

    public static function getPayloadWithTextMarkdownAttachment(): array
    {
        return [
            new WebexChannelTextMarkdownAttachmentTestNotification,
            [
                'headers' => ['Authorization' => 'Bearer '.'token'],
                'json' => [
                    'toPersonEmail' => 'email@example.com',
                    'text' => 'The message, in plain text.',
                    'markdown' => '# The message, in Markdown format.',
                    'attachments' => [
                        [
                            'contentType' => 'application/vnd.microsoft.card.adaptive',
                            'content' => [
                                'type' => 'AdaptiveCard',
                                'version' => '1.0',
                                'body' => [
                                    [
                                        'type' => 'TextBlock',
                                        'text' => 'Adaptive Cards',
                                        'size' => 'large',
                                    ],
                                ],
                                'actions' => [
                                    [
                                        'type' => 'Action.OpenUrl',
                                        'url' => 'https://adaptivecards.io',
                                        'title' => 'Learn More',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->guzzleHttp = m::mock(Client::class);

        $this->webexChannel = new WebexChannel($this->guzzleHttp, 'url', 'id', 'token');
    }

    protected function tearDown(): void
    {
        m::close();
    }
}

/**
 * A dummy notifiable.
 */
class WebexChannelTestNotifiable
{
    use Notifiable;

    public function routeNotificationForWebex()
    {
        return 'email@example.com';
    }
}

/**
 * Webex message notification with text field alone.
 */
class WebexChannelTextTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->text('The message, in plain text.');
    }
}

/**
 * Webex message notification with markdown field alone.
 */
class WebexChannelMarkdownTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->markdown('# The message, in Markdown format.');
    }
}

/**
 * Webex message notification with files field alone.
 */
class WebexChannelFileTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->file(function (WebexMessageFile $file) {
                $file->path(__DIR__.'/fixtures/file.txt');
            });
    }
}

/**
 * Webex message notification with attachment field alone.
 */
class WebexChannelAttachmentTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment->content([
                    'type' => 'AdaptiveCard',
                    'version' => '1.0',
                    'body' => [
                        [
                            'type' => 'TextBlock',
                            'text' => 'Adaptive Cards',
                            'size' => 'large',
                        ],
                    ],
                    'actions' => [
                        [
                            'type' => 'Action.OpenUrl',
                            'url' => 'https://adaptivecards.io',
                            'title' => 'Learn More',
                        ],
                    ],
                ]);
            });
    }
}

/**
 * Webex message notification with text and markdown fields.
 */
class WebexChannelTextMarkdownTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->text('The message, in plain text.')
            ->markdown('# The message, in Markdown format.');
    }
}

/**
 * Webex message notification with text and files fields.
 */
class WebexChannelTextFileTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->text('The message, in plain text.')
            ->file(function (WebexMessageFile $file) {
                $file->path(__DIR__.'/fixtures/file.txt');
            });
    }
}

/**
 * Webex message notification with text and attachments fields.
 */
class WebexChannelTextAttachmentTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->text('The message, in plain text.')
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment->content([
                    'type' => 'AdaptiveCard',
                    'version' => '1.0',
                    'body' => [
                        [
                            'type' => 'TextBlock',
                            'text' => 'Adaptive Cards',
                            'size' => 'large',
                        ],
                    ],
                    'actions' => [
                        [
                            'type' => 'Action.OpenUrl',
                            'url' => 'https://adaptivecards.io',
                            'title' => 'Learn More',
                        ],
                    ],
                ]);
            });
    }
}

/**
 * Webex message notification with markdown and files fields.
 */
class WebexChannelMarkdownFileTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->markdown('# The message, in Markdown format.')
            ->file(function (WebexMessageFile $file) {
                $file->path(__DIR__.'/fixtures/file.txt');
            });
    }
}

/**
 * Webex message notification with markdown and attachments fields.
 */
class WebexChannelMarkdownAttachmentTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->markdown('# The message, in Markdown format.')
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment->content([
                    'type' => 'AdaptiveCard',
                    'version' => '1.0',
                    'body' => [
                        [
                            'type' => 'TextBlock',
                            'text' => 'Adaptive Cards',
                            'size' => 'large',
                        ],
                    ],
                    'actions' => [
                        [
                            'type' => 'Action.OpenUrl',
                            'url' => 'https://adaptivecards.io',
                            'title' => 'Learn More',
                        ],
                    ],
                ]);
            });
    }
}

/**
 * Webex message notification with text, markdown, and files fields.
 */
class WebexChannelTextMarkdownFileTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->text('The message, in plain text.')
            ->markdown('# The message, in Markdown format.')
            ->file(function (WebexMessageFile $file) {
                $file->path(__DIR__.'/fixtures/file.txt');
            });
    }
}

/**
 * Webex message notification with text, markdown, and attachments fields.
 */
class WebexChannelTextMarkdownAttachmentTestNotification extends Notification
{
    public function toWebex($notifiable): WebexMessage
    {
        return (new WebexMessage)
            ->text('The message, in plain text.')
            ->markdown('# The message, in Markdown format.')
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment->content([
                    'type' => 'AdaptiveCard',
                    'version' => '1.0',
                    'body' => [
                        [
                            'type' => 'TextBlock',
                            'text' => 'Adaptive Cards',
                            'size' => 'large',
                        ],
                    ],
                    'actions' => [
                        [
                            'type' => 'Action.OpenUrl',
                            'url' => 'https://adaptivecards.io',
                            'title' => 'Learn More',
                        ],
                    ],
                ]);
            });
    }
}
