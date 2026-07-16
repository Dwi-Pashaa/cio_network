<?php

namespace App\Helpers;

class MacAddressHelper
{
    /**
     * Normalize a MAC address to standard uppercase colon-separated format.
     *
     * Converts any of these formats to AA:BB:CC:DD:EE:FF:
     *   - aa:bb:cc:dd:ee:ff  (lowercase colon)
     *   - AA-BB-CC-DD-EE-FF  (uppercase hyphen)
     *   - aa-bb-cc-dd-ee-ff  (lowercase hyphen)
     *
     * @param  string|null  $mac
     * @return string  Normalized MAC address, or empty string if input is null/empty.
     */
    public static function normalize(?string $mac): string
    {
        if ($mac === null || trim($mac) === '') {
            return '';
        }

        // Replace hyphens with colons, trim whitespace, convert to uppercase
        return strtoupper(trim(str_replace('-', ':', $mac)));
    }
}
