<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NormalizeWhitespace implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // This rule doesn't fail validation, it just normalizes the value
        // The actual normalization happens in the controller
    }

    /**
     * Normalize whitespace in the given value
     *
     * @param mixed $value
     * @return string
     */
    public static function normalize($value): string
    {
        if (!is_string($value)) {
            return $value;
        }

        // Replace multiple consecutive spaces with single space
        $normalized = preg_replace('/\s+/', ' ', $value);
        
        // Trim leading and trailing whitespace
        $normalized = trim($normalized);
        
        return $normalized;
    }
}
