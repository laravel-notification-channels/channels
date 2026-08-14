<?php

use NotificationChannels\OneWaySms\OneWaySmsMessage;

it('accepts content through the constructor', function () {
    expect((new OneWaySmsMessage('Hello'))->content)->toBe('Hello');
});

it('builds fluently', function () {
    $message = OneWaySmsMessage::create()->content('Hello')->sender('MYSENDER');

    expect($message->content)->toBe('Hello')
        ->and($message->sender)->toBe('MYSENDER');
});

it('has no sender until one is set', function () {
    expect(OneWaySmsMessage::create('Hello')->sender)->toBeNull();
});

it('detects the language type from the content', function (string $content, int $expected) {
    expect(OneWaySmsMessage::create($content)->languageType())->toBe($expected);
})->with([
    ['Hello world', OneWaySmsMessage::LANGUAGE_TYPE_TEXT],
    ['Order #123 confirmed!', OneWaySmsMessage::LANGUAGE_TYPE_TEXT],
    ['人', OneWaySmsMessage::LANGUAGE_TYPE_UNICODE],
    ['Ünïcode', OneWaySmsMessage::LANGUAGE_TYPE_UNICODE],
]);

it('lets an explicit choice override detection', function () {
    expect(OneWaySmsMessage::create('人')->text()->languageType())
        ->toBe(OneWaySmsMessage::LANGUAGE_TYPE_TEXT)
        ->and(OneWaySmsMessage::create('Hello')->unicode()->languageType())
        ->toBe(OneWaySmsMessage::LANGUAGE_TYPE_UNICODE);
});

it('leaves text content unencoded', function () {
    expect(OneWaySmsMessage::create('Hello')->encodedContent())->toBe('Hello');
});

it('hex encodes unicode content using the vendor documented vectors', function (string $content, string $expected) {
    expect(OneWaySmsMessage::create($content)->unicode()->encodedContent())->toBe($expected);
})->with([
    ['hello', '00680065006C006C006F'],
    ['a', '0061'],
    ['人', '4EBA'],
]);

it('pads ascii characters to four hex digits as the vendor doc requires', function () {
    // The doc warns that "a" must encode as 0061, never 61.
    expect(OneWaySmsMessage::create('a')->unicode()->encodedContent())->toHaveLength(4);
});

it('encodes astral characters as utf-16 surrogate pairs', function () {
    expect(OneWaySmsMessage::create('🎉')->encodedContent())->toBe('D83CDF89');
});

it('auto encodes when the content triggers unicode detection', function () {
    expect(OneWaySmsMessage::create('人')->encodedContent())->toBe('4EBA');
});
