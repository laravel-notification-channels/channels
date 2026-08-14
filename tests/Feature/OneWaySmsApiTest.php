<?php

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use NotificationChannels\OneWaySms\Exceptions\CouldNotSendNotification;

it('sends an MT request carrying every documented parameter', function () {
    $history = [];
    $api = oneWaySmsApi([new Response(200, [], '200806150001')], $history);

    $api->send(oneWaySmsPayload());

    $request = $history[0]['request'];
    parse_str($request->getUri()->getQuery(), $query);

    expect($request->getMethod())->toBe('GET')
        ->and($request->getUri()->getHost())->toBe('gateway.test')
        ->and($request->getUri()->getPath())->toBe('/api.aspx')
        ->and($query)->toBe([
            'apiusername' => 'test-user',
            'apipassword' => 'test-pass',
            'mobileno' => '60121234567',
            'senderid' => 'MYSENDER',
            'languagetype' => '1',
            'message' => 'Hello',
        ]);
});

it('returns the MT id for a single recipient', function () {
    $api = oneWaySmsApi([new Response(200, [], '200806150001')]);

    expect($api->send(oneWaySmsPayload()))->toBe(['200806150001']);
});

it('returns one MT id per recipient', function () {
    $api = oneWaySmsApi([new Response(200, [], '200806150001,200806150002')]);

    expect($api->send(oneWaySmsPayload()))->toBe(['200806150001', '200806150002']);
});

it('tolerates surrounding whitespace in the response body', function () {
    $api = oneWaySmsApi([new Response(200, [], "  200806150001\r\n")]);

    expect($api->send(oneWaySmsPayload()))->toBe(['200806150001']);
});

it('maps every documented send error code to an exception', function (int $code) {
    $api = oneWaySmsApi([new Response(200, [], (string) $code)]);

    try {
        $api->send(oneWaySmsPayload());
        test()->fail('Expected CouldNotSendNotification to be thrown.');
    } catch (CouldNotSendNotification $exception) {
        expect($exception->gatewayCode())->toBe($code)
            ->and($exception->getMessage())->not->toBeEmpty();
    }
})->with([-100, -200, -300, -400, -500, -600]);

it('maps an undocumented negative code to the unknown-code exception', function () {
    $api = oneWaySmsApi([new Response(200, [], '-999')]);

    expect(fn () => $api->send(oneWaySmsPayload()))
        ->toThrow(CouldNotSendNotification::class, 'undocumented error code');
});

it('throws when the response body is not numeric', function () {
    $api = oneWaySmsApi([new Response(200, [], '<html>Service Unavailable</html>')]);

    expect(fn () => $api->send(oneWaySmsPayload()))
        ->toThrow(CouldNotSendNotification::class, 'unexpected response body');
});

it('throws when the response body is empty', function () {
    $api = oneWaySmsApi([new Response(200, [], '')]);

    expect(fn () => $api->send(oneWaySmsPayload()))
        ->toThrow(CouldNotSendNotification::class, 'unexpected response body');
});

it('wraps a transport failure', function () {
    $api = oneWaySmsApi([
        new ConnectException('Connection refused', new Request('GET', 'https://gateway.test')),
    ]);

    expect(fn () => $api->send(oneWaySmsPayload()))
        ->toThrow(CouldNotSendNotification::class, 'The communication with OneWaySMS failed');
});

it('wraps a non-200 response as a transport failure', function () {
    $api = oneWaySmsApi([new Response(503, [], 'Service Unavailable')]);

    expect(fn () => $api->send(oneWaySmsPayload()))
        ->toThrow(CouldNotSendNotification::class, 'The communication with OneWaySMS failed');
});

it('sends hex encoded content unchanged', function () {
    $history = [];
    $api = oneWaySmsApi([new Response(200, [], '1')], $history);

    $api->send([...oneWaySmsPayload(), 'languagetype' => 2, 'message' => '00680065006C006C006F']);

    parse_str($history[0]['request']->getUri()->getQuery(), $query);

    expect($query['message'])->toBe('00680065006C006C006F')
        ->and($query['languagetype'])->toBe('2');
});
