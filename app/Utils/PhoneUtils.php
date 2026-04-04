<?php

namespace App\Utils;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneUtils
{
    /**
     * Format a phone number to E164 format.
     */
    public static function format(?string $phone, string $country = 'BE'): ?string
    {
        if (! $phone) {
            return null;
        }

        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $number = $phoneUtil->parse($phone, $country);

            if (! $phoneUtil->isValidNumber($number)) {
                return null;
            }

            return $phoneUtil->format($number, PhoneNumberFormat::E164);
        } catch (NumberParseException) {
            return null;
        }
    }
}
