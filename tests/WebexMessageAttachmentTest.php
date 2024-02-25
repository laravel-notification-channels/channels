<?php

namespace NotificationChannels\Webex\Test;

use NotificationChannels\Webex\WebexMessageAttachment;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for WebexMessageAttachment.
 *
 * @backupStaticAttributes enabled
 */
class WebexMessageAttachmentTest extends TestCase
{
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

    public function testContentType()
    {
        $attachmentWithDefaultContentType = (new WebexMessageAttachment);
        $this->assertEquals(
            'application/vnd.microsoft.card.adaptive',
            $attachmentWithDefaultContentType->contentType
        );

        $attachmentWithUserProvidedContentType = (new WebexMessageAttachment)
            ->contentType('user_provided_content_type');
        $this->assertEquals(
            'user_provided_content_type',
            $attachmentWithUserProvidedContentType->contentType
        );
    }

    public function testContent()
    {
        $attachmentWithCard = (new WebexMessageAttachment)->content(self::$card);
        $this->assertEquals(self::$card, $attachmentWithCard->content);
    }

    public function testToArray()
    {
        $expectedArr = [
            'name' => 'attachments',
            'contents' => json_encode([
                'contentType' => 'user_provided_content_type',
                'content' => self::$card,
            ]),
            'headers' => ['Content-Type' => 'application/json'],
        ];
        $arr = (new WebexMessageAttachment)
            ->contentType('user_provided_content_type')
            ->content(self::$card)
            ->toArray();
        $this->assertEquals($expectedArr, $arr);
    }

    public function testJsonSerialize()
    {
        $expectedArr = [
            'contentType' => 'user_provided_content_type',
            'content' => self::$card,
        ];
        $arr = (new WebexMessageAttachment)
            ->contentType('user_provided_content_type')
            ->content(self::$card)
            ->jsonSerialize();
        $this->assertEquals($expectedArr, $arr);
    }

    public function testToJson()
    {
        $expectedJson = json_encode([
            'contentType' => 'user_provided_content_type',
            'content' => self::$card,
        ]);
        $json = (new WebexMessageAttachment)
            ->contentType('user_provided_content_type')
            ->content(self::$card)
            ->toJson();
        $this->assertEquals($expectedJson, $json);
    }
}
