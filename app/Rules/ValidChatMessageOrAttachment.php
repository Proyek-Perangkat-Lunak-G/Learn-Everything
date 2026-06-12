<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidChatMessageOrAttachment implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check: message must be provided OR attachment must be provided (or both)
        $hasMessage = !empty($value); // $value is the message field
        $hasAttachment = request()->hasFile('attachment');

        if (!$hasMessage && !$hasAttachment) {
            $fail('Pesan atau file tidak boleh kosong.');
        }
    }
}
