<?php

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\OneWaySms\OneWaySmsApi;
use NotificationChannels\OneWaySms\OneWaySmsChannel;

/**
 * Bind a mocked HttpClient into the container. $history is populated, by
 * reference, with the requests the resolved OneWaySmsApi issues.
 *
 * @param  array<int, mixed>  $history
 */
function bindMockedHttpClient(array &$history): void
{
    $stack = HandlerStack::create(new MockHandler([new Response(200, [], '100')]));
    $stack->push(Middleware::history($history));

    app()->bind(HttpClient::class, fn () => new HttpClient(['handler' => $stack]));
}

it('registers the one_way_sms notification driver', function () {
    expect(Notification::driver('one_way_sms'))->toBeInstanceOf(OneWaySmsChannel::class);
});

it('builds the api from the configured endpoint and credentials', function () {
    $history = [];
    bindMockedHttpClient($history);

    app(OneWaySmsApi::class)->checkBalance();

    $uri = $history[0]['request']->getUri();
    parse_str($uri->getQuery(), $query);

    expect($uri->getHost())->toBe('gateway.test')
        ->and($query['apiusername'])->toBe('test-user')
        ->and($query['apipassword'])->toBe('test-pass');
});

it('falls back to the default endpoint when none is configured', function () {
    config()->set('services.onewaysms.endpoint', null);
    $history = [];
    bindMockedHttpClient($history);

    app(OneWaySmsApi::class)->checkBalance();

    $uri = $history[0]['request']->getUri();
    parse_str($uri->getQuery(), $query);

    // The host alone can't tell "fallback logic ran" apart from "no binding
    // exists at all" — OneWaySmsApi's own constructor default for $endpoint
    // is DEFAULT_ENDPOINT, so an unbound, autowired instance lands on the
    // same host by coincidence. Asserting the credentials too closes that
    // gap: they still come from config (test-user/test-pass) only if the
    // provider's binding ran; an autowired instance would carry null.
    expect($uri->getHost())->toBe(parse_url(OneWaySmsApi::DEFAULT_ENDPOINT, PHP_URL_HOST))
        ->and($query['apiusername'])->toBe('test-user')
        ->and($query['apipassword'])->toBe('test-pass');
});
