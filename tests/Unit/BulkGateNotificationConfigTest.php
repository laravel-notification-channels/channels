<?php

declare(strict_types=1);

namespace NotificationChannels\BulkGate\Test\Unit;

use NotificationChannels\BulkGate\Exceptions\InvalidConfigException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class BulkGateNotificationConfigTest extends TestCase
{
    /**
     * @var array
     */
    protected $validConfig;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validConfig = [
            'app_id' => '123456789',
            'app_token' => '123456789',
            'send_unicode' => true,
            'default_country' => 'gb',
            'sender_type' => 'gSystem',
            'sender_id' => '123456789',
        ];
    }

    public function testGetSenderType()
    {
        $config = new \NotificationChannels\BulkGate\BulkGateNotificationConfig($this->validConfig);
        $this->assertEquals('gSystem', $config->getSenderType());
    }

    public function testGetSenderId()
    {
        $config = new \NotificationChannels\BulkGate\BulkGateNotificationConfig($this->validConfig);
        $this->assertEquals('123456789', $config->getSenderId());
    }

    public function testGetAppToken()
    {
        $config = new \NotificationChannels\BulkGate\BulkGateNotificationConfig($this->validConfig);
        $this->assertEquals('123456789', $config->getAppToken());
    }

    public function testGetDefaultCountry()
    {
        $config = new \NotificationChannels\BulkGate\BulkGateNotificationConfig($this->validConfig);
        $this->assertEquals('gb', $config->getDefaultCountry());
    }

    public function testGetAppId()
    {
        $config = new \NotificationChannels\BulkGate\BulkGateNotificationConfig($this->validConfig);
        $this->assertEquals('123456789', $config->getAppId());
        $this->assertIsInt($config->getAppId());
    }

    public function testGetSendUnicode()
    {
        $config = new \NotificationChannels\BulkGate\BulkGateNotificationConfig($this->validConfig);
        $this->assertTrue($config->getSendUnicode());
    }

    public function testThrowExceptionWhenSenderIdTooLong()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Sender ID must be 11 characters or less for this sender type.');

        $invalid_config = new \NotificationChannels\BulkGate\BulkGateNotificationConfig([
            'app_id' => '123456789',
            'app_token' => '123456789',
            'send_unicode' => true,
            'default_country' => 'gb',
            'sender_type' => \BulkGate\Sms\SenderSettings\Gate::GATE_TEXT_SENDER,
            'sender_id' => '0123456789123456789',
        ]);
    }

    public function testThrowExceptionWhenEmptySenderId()
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Sender ID is required for this type of sender. Set sender_id in configuration file or use another sender type.');

        $invalid_config = new \NotificationChannels\BulkGate\BulkGateNotificationConfig([
            'app_id' => '123456789',
            'app_token' => '123456789',
            'send_unicode' => true,
            'default_country' => 'gb',
            'sender_type' => \BulkGate\Sms\SenderSettings\Gate::GATE_TEXT_SENDER,
            'sender_id' => '',
        ]);

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage('Sender ID is required for this type of sender. Set sender_id in configuration file or use another sender type.');

        $invalid_config = new \NotificationChannels\BulkGate\BulkGateNotificationConfig([
            'app_id' => '123456789',
            'app_token' => '123456789',
            'send_unicode' => true,
            'default_country' => 'gb',
            'sender_type' => \BulkGate\Sms\SenderSettings\Gate::GATE_OWN_NUMBER,
            'sender_id' => '',
        ]);
    }

    public function testLongSenderIdWhenSenderIsOwnNumber()
    {
        $config = new \NotificationChannels\BulkGate\BulkGateNotificationConfig([
            'app_id' => '123456789',
            'app_token' => '123456789',
            'send_unicode' => true,
            'default_country' => 'gb',
            'sender_type' => \BulkGate\Sms\SenderSettings\Gate::GATE_OWN_NUMBER,
            'sender_id' => '+44123456789',
        ]);

        $this->assertEquals('+44123456789', $config->getSenderId());
    }
}
