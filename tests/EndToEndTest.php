<?php

namespace NotificationChannels\GoogleChat\Tests;

use Illuminate\Support\Facades\Notification;
use NotificationChannels\GoogleChat\Tests\Fixtures\TestEndToEndNotification;

class EndToEndTest extends TestCase
{
    /**
     * @var \NotificationChannels\GoogleChat\Tests\Fixtures\TestEndToEndNotification
     */
    protected $notification;

    public function setUp(): void
    {
        parent::setUp();

        $this->notification = new TestEndToEndNotification;
    }

    public function test_it_generates_complete_correct_array_structure()
    {
        $this->assertEquals(
            [
                'text' => 'This is a test end-to-end notification.',
                'cards' => [
                    // Card 1
                    [
                        'header' => [
                            'title' => 'First Card',
                            'subtitle' => 'First Card - Subtitle',
                            'imageUrl' => 'https://picsum.photos/65/65',
                            'imageStyle' => 'IMAGE',
                        ],
                        'sections' => [
                            // Section 1
                            [
                                'widgets' => [
                                    // Text Paragraph Widget
                                    [
                                        'textParagraph' => [
                                            'text' => 'Simple paragraph text',
                                        ],
                                    ],
                                ],
                            ],

                            // Section 2
                            [
                                'widgets' => [
                                    // Key Value Widget
                                    [
                                        'keyValue' => [
                                            'topLabel' => 'Top Label',
                                            'content' => 'Content',
                                            'bottomLabel' => 'Bottom Label',
                                            'icon' => 'TRAIN',
                                            'onClick' => [
                                                'openLink' => [
                                                    'url' => 'https://example.com/key-value-click',
                                                ],
                                            ],
                                            'contentMultiline' => true,
                                            'button' => [
                                                'imageButton' => [
                                                    'iconUrl' => 'https://picsum.photos/64/64',
                                                    'onClick' => [
                                                        'openLink' => [
                                                            'url' => 'https://example.com/key-value-button-click',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],

                                    // Image Widget
                                    [
                                        'image' => [
                                            'imageUrl' => 'https://picsum.photos/300/150',
                                            'onClick' => [
                                                'openLink' => [
                                                    'url' => 'https://example.com/img-clickthrough',
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    // Card 2
                    [
                        'header' => [
                            'title' => 'Second Card',
                            'subtitle' => 'Second Card - Subtitle',
                            'imageUrl' => 'https://picsum.photos/66/66',
                            'imageStyle' => 'AVATAR',
                        ],
                        'sections' => [
                            // Section
                            [
                                'widgets' => [
                                    // Button Widget
                                    [
                                        'buttons' => [
                                            // Text Button
                                            [
                                                'textButton' => [
                                                    'text' => 'Go There',
                                                    'onClick' => [
                                                        'openLink' => [
                                                            'url' => 'https://example.com/card-2-cta',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $this->notification->toGoogleChat('foo')->toArray()
        );
    }

    /** @group external */
    public function test_it_can_send_message_to_google()
    {
        Notification::route('googleChat', env('GOOGLE_CHAT_TEST_SPACE'))
            ->notify($this->notification);

        $this->assertTrue(true);
    }
}
