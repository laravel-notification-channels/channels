<?php

use GuzzleHttp\Psr7\Response;
use NotificationChannels\OneWaySms\Exceptions\CouldNotSendNotification;
use NotificationChannels\OneWaySms\OneWaySmsChannel;
use NotificationChannels\OneWaySms\OneWaySmsMessage;
use NotificationChannels\OneWaySms\Tests\TestSupport\TestNotifiable;
use NotificationChannels\OneWaySms\Tests\TestSupport\TestNotifiableWithoutRoute;
use NotificationChannels\OneWaySms\Tests\TestSupport\TestNotification;
use NotificationChannels\OneWaySms\Tests\TestSupport\TestNotificationWithStringMessage;

it('sends nothing when the notifiable has no route', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([], $history), 'DEFAULT');

    expect($channel->send(new TestNotifiableWithoutRoute, new TestNotification))->toBeNull()
        ->and($history)->toBeEmpty();
});

it('sends nothing when the route is an empty string', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([], $history), 'DEFAULT');

    expect($channel->send(new TestNotifiable(''), new TestNotification))->toBeNull()
        ->and($history)->toBeEmpty();
});

it('sends the message and returns the MT ids', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([new Response(200, [], '200806150001')], $history), 'DEFAULT');

    $result = $channel->send(new TestNotifiable, new TestNotification);

    parse_str($history[0]['request']->getUri()->getQuery(), $query);

    expect($result)->toBe(['200806150001'])
        ->and($query['mobileno'])->toBe('60121234567')
        ->and($query['message'])->toBe('Test message')
        ->and($query['languagetype'])->toBe('1');
});

it('wraps a plain string returned by toOneWaySms', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([new Response(200, [], '1')], $history), 'DEFAULT');

    $channel->send(new TestNotifiable, new TestNotificationWithStringMessage);

    parse_str($history[0]['request']->getUri()->getQuery(), $query);

    expect($query['message'])->toBe('Plain string message');
});

it('joins multiple recipients with commas', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([new Response(200, [], '1,2')], $history), 'DEFAULT');

    $channel->send(new TestNotifiable(['60121234567', '60197654321']), new TestNotification);

    parse_str($history[0]['request']->getUri()->getQuery(), $query);

    expect($query['mobileno'])->toBe('60121234567,60197654321');
});

it('prefers the sender set on the message', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([new Response(200, [], '1')], $history), 'DEFAULT');

    $channel->send(
        new TestNotifiable,
        new TestNotification(OneWaySmsMessage::create('Hi')->sender('OVERRIDE')),
    );

    parse_str($history[0]['request']->getUri()->getQuery(), $query);

    expect($query['senderid'])->toBe('OVERRIDE');
});

it('falls back to the configured sender', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([new Response(200, [], '1')], $history), 'DEFAULT');

    $channel->send(new TestNotifiable, new TestNotification);

    parse_str($history[0]['request']->getUri()->getQuery(), $query);

    expect($query['senderid'])->toBe('DEFAULT');
});

it('hex encodes unicode content before sending', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([new Response(200, [], '1')], $history), 'DEFAULT');

    $channel->send(new TestNotifiable, new TestNotification(OneWaySmsMessage::create('人')));

    parse_str($history[0]['request']->getUri()->getQuery(), $query);

    expect($query['message'])->toBe('4EBA')
        ->and($query['languagetype'])->toBe('2');
});

it('rejects more than ten recipients before making a request', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([], $history), 'DEFAULT');
    $recipients = array_fill(0, 11, '60121234567');

    expect(fn () => $channel->send(new TestNotifiable($recipients), new TestNotification))
        ->toThrow(CouldNotSendNotification::class, 'at most 10 recipients');

    expect($history)->toBeEmpty();
});

it('accepts exactly ten recipients', function () {
    $channel = new OneWaySmsChannel(oneWaySmsApi([new Response(200, [], '1')]), 'DEFAULT');
    $recipients = array_fill(0, 10, '60121234567');

    expect($channel->send(new TestNotifiable($recipients), new TestNotification))->toBe(['1']);
});

it('rejects an empty message before making a request', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([], $history), 'DEFAULT');

    expect(fn () => $channel->send(new TestNotifiable, new TestNotification(OneWaySmsMessage::create('   '))))
        ->toThrow(CouldNotSendNotification::class, 'message content is empty');

    expect($history)->toBeEmpty();
});

it('rejects a missing sender before making a request', function () {
    $history = [];
    $channel = new OneWaySmsChannel(oneWaySmsApi([], $history), null);

    expect(fn () => $channel->send(new TestNotifiable, new TestNotification))
        ->toThrow(CouldNotSendNotification::class, 'No sender ID was given');

    expect($history)->toBeEmpty();
});
