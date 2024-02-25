<?php

namespace NotificationChannels\Webex\Test;

use NotificationChannels\Webex\Exceptions\CouldNotCreateNotification;
use NotificationChannels\Webex\WebexMessage;
use NotificationChannels\Webex\WebexMessageAttachment;
use NotificationChannels\Webex\WebexMessageFile;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for WebexMessageTest.
 *
 * @backupStaticAttributes enabled
 */
class WebexMessageTest extends TestCase
{
    protected static $email = 'email@wxample.com';
    protected static $personId = 'Y2lzY29zcGFyazovL3VzL1BFT1BMRS85OTRmYjAyZS04MWI1LTRmNDItYTllYy1iNzE2OGRlOWUzZjY';
    protected static $botId = 'Y2lzY29zcGFyazovL3VzL0FQUExJQ0FUSU9OLzQxMmRhYjY4LTU3ZDAtNGU0Mi05MTJmLTIzODk2ODcyYTMwMg';
    protected static $roomId = 'Y2lzY29zcGFyazovL3VzL1JPT00vOTU5Y2M0YzAtMjMxNC0xMWVjLWFhMDUtZWYxMmNlMmE5YjJi';
    protected static $text = 'The message, in plain text.';
    protected static $markdown = '# The message, in Markdown format.';
    protected static $parentId = 'Y2lzY29zcGFyazovL3VzL01FU1NBR0UvMjExN2ZjZTAtODcwMS0xMWVjLThjNDgtZmYzMmYwOWExMjNj';
    protected static $filepath = __DIR__.'/fixtures/file.txt';
    protected static $card = [
        'type' => 'AdaptiveCard',
        'body' => [
            [
                'type' => 'ColumnSet',
                'columns' => [
                    [
                        'type' => 'Column',
                        'items' => [
                            [
                                'type' => 'Image',
                                'style' => 'Person',
                                'url' => 'https://developer.webex.com/images/webex-teams-logo.png',
                                'size' => 'Medium',
                                'height' => '50px',
                            ],
                        ],
                        'width' => 'auto',
                    ],
                    [
                        'type' => 'Column',
                        'items' => [
                            [
                                'type' => 'TextBlock',
                                'text' => 'Cisco Webex Teams',
                                'weight' => 'Lighter',
                                'color' => 'Accent',
                            ],
                            [
                                'type' => 'TextBlock',
                                'weight' => 'Bolder',
                                'text' => 'Buttons and Cards Release',
                                'horizontalAlignment' => 'Left',
                                'wrap' => true,
                                'color' => 'Light',
                                'size' => 'Large',
                                'spacing' => 'Small',
                            ],
                        ],
                        'width' => 'stretch',
                    ],
                ],
            ],
            [
                'type' => 'ColumnSet',
                'columns' => [
                    [
                        'type' => 'Column',
                        'width' => 35,
                        'items' => [
                            [
                                'type' => 'TextBlock',
                                'text' => 'Release Date:',
                                'color' => 'Light',
                            ],
                            [
                                'type' => 'TextBlock',
                                'text' => 'Product:',
                                'weight' => 'Lighter',
                                'color' => 'Light',
                                'spacing' => 'Small',
                            ],
                            [
                                'type' => 'TextBlock',
                                'text' => 'OS:',
                                'weight' => 'Lighter',
                                'color' => 'Light',
                                'spacing' => 'Small',
                            ],
                        ],
                    ],
                    [
                        'type' => 'Column',
                        'width' => 65,
                        'items' => [
                            [
                                'type' => 'TextBlock',
                                'text' => 'Aug 6, 2019',
                                'color' => 'Light',
                            ],
                            [
                                'type' => 'TextBlock',
                                'text' => 'Webex Teams',
                                'color' => 'Light',
                                'weight' => 'Lighter',
                                'spacing' => 'Small',
                            ],
                            [
                                'type' => 'TextBlock',
                                'text' => 'Mac, Windows, Web',
                                'weight' => 'Lighter',
                                'color' => 'Light',
                                'spacing' => 'Small',
                            ],
                        ],
                    ],
                ],
                'spacing' => 'Padding',
                'horizontalAlignment' => 'Center',
            ],
            [
                'type' => 'TextBlock',
                'text' => "We're making it easier for you to interact with bots and integrations in Webex Teams.
                            When your bot sends information in a space that includes a card with buttons,
                            you can now easily interact with it.",
                'wrap' => true,
            ],
            [
                'type' => 'TextBlock',
                'text' => 'Buttons and Cards Resources:',
            ],
            [
                'type' => 'ColumnSet',
                'columns' => [
                    [
                        'type' => 'Column',
                        'width' => 'auto',
                        'items' => [
                            [
                                'type' => 'Image',
                                'altText' => '',
                                'url' => 'https://developer.webex.com/images/link-icon.png',
                                'size' => 'Small',
                                'width' => '30px',
                            ],
                        ],
                        'spacing' => 'Small',
                    ],
                    [
                        'type' => 'Column',
                        'width' => 'auto',
                        'items' => [
                            [
                                'type' => 'TextBlock',
                                'text' => '[Developer Portal Buttons and Cards Guide]()',
                                'horizontalAlignment' => 'Left',
                                'size' => 'Medium',
                            ],
                        ],
                        'verticalContentAlignment' => 'Center',
                        'horizontalAlignment' => 'Left',
                        'spacing' => 'Small',
                    ],
                ],
            ],
            [
                'type' => 'ActionSet',
                'actions' => [
                    [
                        'type' => 'Action.Submit',
                        'title' => 'Subscribe to Release Notes',
                        'data' => [
                            'subscribe' => true,
                        ],
                    ],
                ],
                'horizontalAlignment' => 'Left',
                'spacing' => 'None',
            ],
        ],
        '$schema' => 'https://adaptivecards.io/schemas/adaptive-card.json',
        'version' => '1.2',
    ];

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

    public function testTo()
    {
        $messageToEmail = (new WebexMessage)->to(self::$email);
        $this->assertEquals(self::$email, $messageToEmail->toPersonEmail);

        $messageToPersonId = (new WebexMessage)->to(self::$personId);
        $this->assertEquals(self::$personId, $messageToPersonId->toPersonId);

        $messageToBotId = (new WebexMessage)->to(self::$botId);
        $this->assertEquals(self::$botId, $messageToBotId->toPersonId);

        $messageToRoomId = (new WebexMessage)->to(self::$roomId);
        $this->assertEquals(self::$roomId, $messageToRoomId->roomId);

        $this->expectExceptionObject(CouldNotCreateNotification::failedToDetermineRecipient());
        (new WebexMessage)->to('not_a_valid_email_or_an_expected_webex_http_api_id');
    }

    public function testParentId()
    {
        $messageWithValidParentId = (new WebexMessage)->parentId(self::$parentId);
        $this->assertEquals(self::$parentId, $messageWithValidParentId->parentId);

        $invalidParentId = 'not_an_expected_webex_http_api_message_resource_id';
        $this->expectExceptionObject(CouldNotCreateNotification::invalidParentId($invalidParentId));
        (new WebexMessage)->parentId($invalidParentId);
    }

    public function testText()
    {
        $messageWithText = (new WebexMessage)->text(self::$text);
        $this->assertEquals(self::$text, $messageWithText->text);
    }

    public function testMarkdown()
    {
        $messageWithMarkdown = (new WebexMessage)->markdown(self::$markdown);
        $this->assertEquals(self::$markdown, $messageWithMarkdown->markdown);
    }

    public function testFile()
    {
        $messageWithSingleFile = (new WebexMessage)
            ->file(function (WebexMessageFile $file) {
                $file->path(self::$filepath);
            });
        $this->assertEquals(self::$filepath, $messageWithSingleFile->files[0]->path);
    }

    public function testMessageWithMultipleFiles()
    {
        $this->expectExceptionObject(CouldNotCreateNotification::multipleFilesNotSupported());
        (new WebexMessage)
            ->file(function (WebexMessageFile $file) {
                $file->path(self::$filepath);
            })
            ->file(function (WebexMessageFile $file) {
                $file->path(self::$filepath);
            });
    }

    public function testMessageWithAttachmentAndFile()
    {
        $this->expectExceptionObject(CouldNotCreateNotification::messageWithFileAndAttachmentNotSupported());
        (new WebexMessage)
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment->content(self::$card);
            })
            ->file(function (WebexMessageFile $file) {
                $file->path(self::$filepath);
            });
    }

    public function testAttachment()
    {
        $messageWithSingleAttachment = (new WebexMessage)
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment->content(self::$card);
            });
        $this->assertEquals(self::$card, $messageWithSingleAttachment->attachments[0]->content);
    }

    public function testMessageWithMultipleAttachments()
    {
        $this->expectExceptionObject(CouldNotCreateNotification::multipleAttachmentsNotSupported());
        (new WebexMessage)
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment->content(self::$card);
            })
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment->content(self::$card);
            });
    }

    public function testMessageWithFileAndAttachment()
    {
        $this->expectExceptionObject(CouldNotCreateNotification::messageWithFileAndAttachmentNotSupported());
        (new WebexMessage)
            ->file(function (WebexMessageFile $file) {
                $file->path(self::$filepath);
            })
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment->content(self::$card);
            });
    }

    public function testToArray()
    {
        $expectedArr = [
            [
                'name' => 'roomId',
                'contents' => self::$roomId,
            ],
            [
                'name' => 'parentId',
                'contents' => self::$parentId,
            ],
            [
                'name' => 'text',
                'contents' => self::$text,
            ],
            [
                'name' => 'markdown',
                'contents' => self::$markdown,
            ],
            [
                'name' => 'files',
                'contents' => stream_get_contents(fopen(self::$filepath, 'r')),
                'filename' => 'user_provided_name',
                'headers' => ['Content-Type' => 'user_provided_mime_type'],
            ],
        ];
        $arr = (new WebexMessage)
            ->to(self::$roomId)
            ->parentId(self::$parentId)
            ->text(self::$text)
            ->markdown(self::$markdown)
            ->file(function (WebexMessageFile $file) {
                $file->path(self::$filepath)
                    ->name('user_provided_name')
                    ->type('user_provided_mime_type');
            })
            ->toArray();
        array_walk_recursive($arr, [$this, 'getResourceContents']);
        $this->assertEquals($expectedArr, $arr);

        $expectedArr = [
            [
                'name' => 'roomId',
                'contents' => self::$roomId,
            ],
            [
                'name' => 'parentId',
                'contents' => self::$parentId,
            ],
            [
                'name' => 'text',
                'contents' => self::$text,
            ],
            [
                'name' => 'markdown',
                'contents' => self::$markdown,
            ],
            [
                'name' => 'attachments',
                'contents' => json_encode([
                    'contentType' => 'user_provided_content_type',
                    'content' => self::$card,
                ]),
                'headers' => ['Content-Type' => 'application/json'],
            ],
        ];
        $arr = (new WebexMessage)
            ->to(self::$roomId)
            ->parentId(self::$parentId)
            ->text(self::$text)
            ->markdown(self::$markdown)
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment
                    ->contentType('user_provided_content_type')
                    ->content(self::$card);
            })
            ->toArray();
        $this->assertEquals($expectedArr, $arr);

        $expectedArr = [
            [
                'name' => 'files',
                'contents' => stream_get_contents(fopen(self::$filepath, 'r')),
                'filename' => 'user_provided_name',
                'headers' => ['Content-Type' => 'user_provided_mime_type'],
            ],
            [
                'name' => 'files',
                'contents' => stream_get_contents(fopen(self::$filepath, 'r')),
                'filename' => 'user_provided_name',
                'headers' => ['Content-Type' => 'user_provided_mime_type'],
            ],
        ];
        $msg = new WebexMessage();
        $msg->files[] = (new WebexMessageFile)
            ->path(self::$filepath)
            ->name('user_provided_name')
            ->type('user_provided_mime_type');
        $msg->files[] = (new WebexMessageFile)
            ->path(self::$filepath)
            ->name('user_provided_name')
            ->type('user_provided_mime_type');
        $arr = $msg->toArray();
        array_walk_recursive($arr, [$this, 'getResourceContents']);
        $this->assertEquals($expectedArr, $arr);

        $expectedArr = [
            [
                'name' => 'attachments',
                'contents' => json_encode([
                    'contentType' => 'user_provided_content_type',
                    'content' => self::$card,
                ]),
                'headers' => ['Content-Type' => 'application/json'],
            ],
            [
                'name' => 'attachments',
                'contents' => json_encode([
                    'contentType' => 'user_provided_content_type',
                    'content' => self::$card,
                ]),
                'headers' => ['Content-Type' => 'application/json'],
            ],
        ];
        $msg = new WebexMessage();
        $msg->attachments[] = (new WebexMessageAttachment)
            ->contentType('user_provided_content_type')
            ->content(self::$card);
        $msg->attachments[] = (new WebexMessageAttachment)
            ->contentType('user_provided_content_type')
            ->content(self::$card);
        $arr = $msg->toArray();
        $this->assertEquals($expectedArr, $arr);

        $expectedArr = [
            [
                'name' => 'files',
                'contents' => stream_get_contents(fopen(self::$filepath, 'r')),
                'filename' => 'user_provided_name',
                'headers' => ['Content-Type' => 'user_provided_mime_type'],
            ],
            [
                'name' => 'attachments',
                'contents' => json_encode([
                    'contentType' => 'user_provided_content_type',
                    'content' => self::$card,
                ]),
                'headers' => ['Content-Type' => 'application/json'],
            ],
        ];
        $msg = new WebexMessage();
        $msg->files[] = (new WebexMessageFile)
            ->path(self::$filepath)
            ->name('user_provided_name')
            ->type('user_provided_mime_type');
        $msg->attachments[] = (new WebexMessageAttachment)
            ->contentType('user_provided_content_type')
            ->content(self::$card);
        $arr = $msg->toArray();
        array_walk_recursive($arr, [$this, 'getResourceContents']);
        $this->assertEquals($expectedArr, $arr);
    }

    public function testJsonSerialize()
    {
        $arr = (new WebexMessage)
            ->to(self::$roomId)
            ->parentId(self::$parentId)
            ->text(self::$text)
            ->markdown(self::$markdown)
            ->file(function (WebexMessageFile $file) {
                $file->path(self::$filepath)
                    ->name('user_provided_name')
                    ->type('user_provided_mime_type');
            })
            ->jsonSerialize();
        $this->assertArrayNotHasKey('files', $arr);

        $expectedArr = [
            'roomId' => self::$roomId,
            'parentId' => self::$parentId,
            'text' => self::$text,
            'markdown' => self::$markdown,
            'attachments' => [
                [
                    'contentType' => 'user_provided_content_type',
                    'content' => self::$card,
                ],
            ],
        ];
        $arr = (new WebexMessage)
            ->to(self::$roomId)
            ->parentId(self::$parentId)
            ->text(self::$text)
            ->markdown(self::$markdown)
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment
                    ->contentType('user_provided_content_type')
                    ->content(self::$card);
            })
            ->jsonSerialize();
        $this->assertEquals($expectedArr, $arr);
    }

    public function testToJson()
    {
        $expectedJson = json_encode([
            'roomId' => self::$roomId,
            'parentId' => self::$parentId,
            'text' => self::$text,
            'markdown' => self::$markdown,
        ]);
        $json = (new WebexMessage)
            ->to(self::$roomId)
            ->parentId(self::$parentId)
            ->text(self::$text)
            ->markdown(self::$markdown)
            ->file(function (WebexMessageFile $file) {
                $file->path(self::$filepath)
                    ->name('user_provided_name')
                    ->type('user_provided_mime_type');
            })
            ->toJson();
        $this->assertEquals($expectedJson, $json);

        $expectedJson = json_encode([
            'roomId' => self::$roomId,
            'parentId' => self::$parentId,
            'text' => self::$text,
            'markdown' => self::$markdown,
            'attachments' => [
                [
                    'contentType' => 'user_provided_content_type',
                    'content' => self::$card,
                ],
            ],
        ]);
        $json = (new WebexMessage)
            ->to(self::$roomId)
            ->parentId(self::$parentId)
            ->text(self::$text)
            ->markdown(self::$markdown)
            ->attachment(function (WebexMessageAttachment $attachment) {
                $attachment
                    ->contentType('user_provided_content_type')
                    ->content(self::$card);
            })
            ->toJson();
        $this->assertEquals($expectedJson, $json);
    }
}
