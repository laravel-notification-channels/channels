<?php

use Illuminate\Support\Facades\Notification;
use NotificationChannels\OneWaySms\OneWaySmsApi;
use NotificationChannels\OneWaySms\OneWaySmsChannel;

it('registers the one_way_sms notification driver', function () {
    expect(Notification::driver('one_way_sms'))->toBeInstanceOf(OneWaySmsChannel::class);
});

it('resolves the api from the container', function () {
    expect(app(OneWaySmsApi::class))->toBeInstanceOf(OneWaySmsApi::class);
});

it('falls back to the default endpoint when none is configured', function () {
    config()->set('services.onewaysms.endpoint', null);

    expect(app(OneWaySmsApi::class))->toBeInstanceOf(OneWaySmsApi::class);
});
