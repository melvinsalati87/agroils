<?php

namespace App\Helpers;

class PhoneValidation
{
    /**
     * Standardize a phone number to the 260 format.
     * Examples:
     * - 0971234567 -> 260971234567
     * - +260971234567 -> 260971234567
     * - 971234567 -> 260971234567
     */
    public static function sanitizePhoneNumber(string $phone): string
    {
        // 1. Strip away everything except digits (+, spaces, hyphens)
        $phone = preg_replace('/\D/', '', $phone);

        // 2. If it starts with 0 (e.g., 097...), strip the 0 and prepend 260
        if (str_starts_with($phone, '0')) {
            return '260' . substr($phone, 1);
        }

        // 3. If it already starts with 260, return it directly
        if (str_starts_with($phone, '260')) {
            return $phone;
        }

        // 4. If it's a 9-digit number starting with 7 or 9 (e.g., 971234567), prepend 260
        if (strlen($phone) === 9 && (str_starts_with($phone, '7') || str_starts_with($phone, '9'))) {
            return '260' . $phone;
        }

        // Return original if it doesn't match expected patterns
        return $phone;
    }
}
