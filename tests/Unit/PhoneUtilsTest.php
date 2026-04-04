<?php

use App\Utils\PhoneUtils;

test('it formats Belgian phone numbers to E164', function () {
    expect(PhoneUtils::format('0470123456', 'BE'))->toBe('+32470123456');
    expect(PhoneUtils::format('+32 470 12 34 56', 'BE'))->toBe('+32470123456');
    expect(PhoneUtils::format('02/201.22.11', 'BE'))->toBe('+3222012211');
});

test('it formats French phone numbers to E164', function () {
    expect(PhoneUtils::format('0612345678', 'FR'))->toBe('+33612345678');
});

test('it returns null for invalid numbers', function () {
    expect(PhoneUtils::format('not a phone number', 'BE'))->toBeNull();
    expect(PhoneUtils::format('123', 'BE'))->toBeNull();
});

test('it returns null for empty input', function () {
    expect(PhoneUtils::format(null, 'BE'))->toBeNull();
    expect(PhoneUtils::format('', 'BE'))->toBeNull();
});
