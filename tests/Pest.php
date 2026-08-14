<?php

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use NotificationChannels\OneWaySms\OneWaySmsApi;
use NotificationChannels\OneWaySms\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * Build an API client backed by a queue of canned Guzzle responses.
 *
 * @param  array<int, mixed>  $queue
 * @param  array<int, mixed>  $history  populated with the requests that were sent
 */
function oneWaySmsApi(array $queue, array &$history = []): OneWaySmsApi
{
    $stack = HandlerStack::create(new MockHandler($queue));
    $stack->push(Middleware::history($history));

    return new OneWaySmsApi(
        new HttpClient(['handler' => $stack]),
        'https://gateway.test',
        'test-user',
        'test-pass',
    );
}

/**
 * The minimum valid payload for OneWaySmsApi::send().
 *
 * @return array{to: string, sender: string, languagetype: int, message: string}
 */
function oneWaySmsPayload(): array
{
    return [
        'to' => '60121234567',
        'sender' => 'MYSENDER',
        'languagetype' => 1,
        'message' => 'Hello',
    ];
}
