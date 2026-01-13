<?php

namespace Core;

class Validator
{
    /**
     * Validate a string's length after trimming.
     * Returns true if length is between min and max (inclusive).
     */
    public static function string($value, $min = 1, $max = INF): bool
    {
        $value = trim($value);

        return strlen($value) >= $min && strlen($value) <= $max;
    }

    /**
     * Validate an email address. Returns bool.
     */
    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check that an integer is greater than another.
     */
    public static function greaterThan(int $value, int $greaterThan): bool
    {
        return $value > $greaterThan;
    }
}
