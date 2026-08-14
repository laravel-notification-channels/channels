<?php

it('boots the testbench application with package config', function () {
    expect(config('services.onewaysms.endpoint'))->toBe('https://gateway.test');
});
