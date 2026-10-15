<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ErpIdentifierService
{
    /**
     * Produce a secure numeric One-Time Password (OTP).
     */
    public static function createOtp(int $length = 6): string
    {
        if ($length < 4 || $length > 8) {
            $length = 6;
        }

        $min = 10 ** ($length - 1);
        $max = (10 ** $length) - 1;

        return (string) random_int($min, $max);
    }

    /**
     * Produce a unique Grower Number with a custom corporate prefix.
     * Example: AGRO-GR-2026-839401
     */
    public static function createGrowerNumber(string $table = 'users', string $column = 'grower_number'): string
    {
        $prefix = 'AGRO-GR-' . date('Y') . '-';

        do {
            $suffix = (string) random_int(100000, 999999);
            $growerNumber = $prefix . $suffix;

            $exists = DB::table($table)->where($column, $growerNumber)->exists();
        } while ($exists);

        return $growerNumber;
    }

    /**
     * Produce a unique Transaction or System Reference Number.
     * Example: REF-1786523904B
     */
    public static function createReferenceNumber(string $prefix = 'REF', string $table = 'transactions', string $column = 'reference_number'): string
    {
        $cleanPrefix = strtoupper(trim($prefix));

        do {
            $numericSegment = (string) random_int(100000000, 999999999);
            $alphaSegment = chr(random_int(65, 90));

            $reference = $cleanPrefix . '-' . $numericSegment . $alphaSegment;

            $exists = DB::table($table)->where($column, $reference)->exists();
        } while ($exists);

        return $reference;
    }
}
