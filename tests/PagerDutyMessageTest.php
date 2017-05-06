<?php

namespace NotificationChannels\PagerDuty\Test;

use NotificationChannels\PagerDuty\PagerDutyMessage;
use Orchestra\Testbench\TestCase;

class PagerDutyMessageTest extends TestCase
{
    /** @test */
    public function basic_message_has_all_values()
    {
        $message = (new PagerDutyMessage())
            ->routingKey('testIntegration01')
            ->summary('This is a test message');

        $body = $message->toArray();

        $this->assertArrayHasKey('event_action', $body);
        $this->assertArrayHasKey('routing_key', $body);
        $this->assertArrayHasKey('payload', $body);
        $this->assertArrayHasKey('source', $body['payload']);
        $this->assertArrayHasKey('severity', $body['payload']);
        $this->assertArrayHasKey('summary', $body['payload']);

        $this->assertTrue(is_string($body['payload']['source']));
    }

    /** @test */
    public function test_message_renders()
    {
        $message = (new PagerDutyMessage())
            ->routingKey('testIntegration01')
            ->summary('This is a test message')
            ->source('testSource');

        $this->assertEquals(
            [
                'event_action' => 'trigger',
                'routing_key' => 'testIntegration01',
                'payload' => [
                    'source' => 'testSource',
                    'severity' => 'critical',
                    'summary' => 'This is a test message',
                ],
            ], $message->toArray()
        );
    }

    /** @test */
    public function test_message_renders_optional_params()
    {
        $message = (new PagerDutyMessage())
            ->routingKey('testIntegration01')
            ->dedupKey('testMessage01')
            ->severity('error')
            ->timestamp('timestamp')
            ->component('nginx')
            ->setClass('ping failure')
            ->summary('This is a test message')
            ->source('testSource');

        $this->assertEquals(
            [
                'event_action' => 'trigger',
                'routing_key' => 'testIntegration01',
                'payload' => [
                    'source' => 'testSource',
                    'severity' => 'error',
                    'summary' => 'This is a test message',
                    'timestamp' => 'timestamp',
                    'component' => 'nginx',
                    'class' => 'ping failure',
                ],
                'dedup_key' => 'testMessage01',
            ], $message->toArray()
        );
    }

    /** @test */
    public function test_message_renders_custom_details()
    {
        $message = (new PagerDutyMessage())
            ->routingKey('testIntegration01')
            ->summary('This is a test message')
            ->source('testSource')
        ->addCustomDetail('ping time', '1500ms')
        ->addCustomDetail('load avg', '0.75');

        $this->assertEquals(
            [
                'event_action' => 'trigger',
                'routing_key' => 'testIntegration01',
                'payload' => [
                    'source' => 'testSource',
                    'severity' => 'critical',
                    'summary' => 'This is a test message',
                    'custom_details' => [
                        'ping time' => '1500ms',
                        'load avg' => '0.75',
                    ],
                ],
            ], $message->toArray()
        );
    }

    /** @test */
    public function test_message_renders_resolve()
    {
        $message = (new PagerDutyMessage())
            ->routingKey('testIntegration01')
            ->source('testSource')
            ->dedupKey('testMessage01')
            ->resolve();

        $this->assertEquals(
            [
                'event_action' => 'resolve',
                'routing_key' => 'testIntegration01',
                'payload' => [
                    'source' => 'testSource',
                    'severity' => 'critical',
                ],
                'dedup_key' => 'testMessage01',
            ], $message->toArray()
        );
    }
}
