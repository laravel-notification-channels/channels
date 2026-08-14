<?php

use NotificationChannels\OneWaySms\Exceptions\CouldNotSendNotification;

it('records the gateway code for each documented gateway failure', function (string $factory, int $expectedCode) {
    $exception = CouldNotSendNotification::$factory();

    expect($exception->gatewayCode())->toBe($expectedCode)
        ->and($exception->getCode())->toBe(abs($expectedCode))
        ->and($exception->getMessage())->not->toBeEmpty();
})->with([
    ['invalidCredentials', -100],
    ['invalidSenderId', -200],
    ['invalidRecipient', -300],
    ['invalidLanguageType', -400],
    ['invalidMessageCharacters', -500],
    ['insufficientCredit', -600],
]);

it('records an undocumented gateway code', function () {
    $exception = CouldNotSendNotification::unknownErrorCode(-999);

    expect($exception->gatewayCode())->toBe(-999)
        ->and($exception->getMessage())->toContain('-999');
});

it('has no gateway code for locally raised failures', function (CouldNotSendNotification $exception) {
    expect($exception->gatewayCode())->toBeNull();
})->with([
    fn () => CouldNotSendNotification::emptyMessage(),
    fn () => CouldNotSendNotification::missingSender(),
    fn () => CouldNotSendNotification::tooManyRecipients(11, 10),
    fn () => CouldNotSendNotification::unexpectedResponse('<html>'),
]);

it('states the recipient limit and the offending count', function () {
    expect(CouldNotSendNotification::tooManyRecipients(11, 10)->getMessage())
        ->toContain('10')
        ->toContain('11');
});

it('quotes the unexpected response body', function () {
    expect(CouldNotSendNotification::unexpectedResponse('<html>oops</html>')->getMessage())
        ->toContain('<html>oops</html>');
});

it('wraps a transport exception as the previous exception', function () {
    $previous = new RuntimeException('Connection refused');

    $exception = CouldNotSendNotification::couldNotCommunicateWithOneWaySms($previous);

    expect($exception->getPrevious())->toBe($previous)
        ->and($exception->getMessage())->toContain('Connection refused');
});

it('redacts gateway credentials from a wrapped transport exception regardless of parameter order', function () {
    $previous = new RuntimeException(
        'cURL error 7: Failed to connect for https://gateway.test/bulkcredit.aspx?apipassword=p%40ss123&apiusername=my-secret-user&keepme=visible'
    );

    $exception = CouldNotSendNotification::couldNotCommunicateWithOneWaySms($previous);

    expect($exception->getMessage())
        ->not->toContain('p%40ss123')
        ->not->toContain('my-secret-user')
        ->toContain('apipassword=***REDACTED***')
        ->toContain('apiusername=***REDACTED***')
        ->toContain('keepme=visible')
        ->toContain('cURL error 7')
        ->toContain('gateway.test/bulkcredit.aspx');
});

it('redacts credentials from an unexpected response body that echoes the request URL', function () {
    $exception = CouldNotSendNotification::unexpectedResponse(
        'Bad request for /bulkcredit.aspx?apiusername=my-secret-user&apipassword=p%40ss123'
    );

    expect($exception->getMessage())
        ->not->toContain('my-secret-user')
        ->not->toContain('p%40ss123')
        ->toContain('apiusername=***REDACTED***')
        ->toContain('apipassword=***REDACTED***')
        ->toContain('/bulkcredit.aspx');
});
