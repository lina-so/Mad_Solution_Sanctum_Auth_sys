<?php

namespace App\Rules\PhoneNumber;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class PhoneNumberRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match("/^\b963\s\d{9}\b$/", $value)) {
            $fail("The $attribute format is invalid. Example: 963 985537632");
        }
    }

}
