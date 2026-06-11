<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Custom Rule untuk validasi kombinasi message dan attachment
 * 
 * Aturan:
 * - Message berisi (1-500 chars) DAN tanpa attachment = VALID
 * - Message kosong DAN ada attachment <= 10MB = VALID
 * - Message berisi (1-500 chars) DAN ada attachment <= 10MB = VALID
 * - Message kosong DAN tanpa attachment = INVALID
 */
class ValidChatMessageOrAttachment implements Rule
{
    protected $message = '';

    public function passes($attribute, $value)
    {
        // Jika ini rule untuk message, check kombinasinya
        if ($attribute === 'message') {
            $hasMessage = !empty($value) && strlen($value) > 0;
            $hasAttachment = request()->hasFile('attachment') && request()->file('attachment')->isValid();

            // Kombinasi valid:
            // 1. Ada message (panjang sudah divalidasi di rules lain)
            // 2. Atau ada attachment
            if ($hasMessage || $hasAttachment) {
                return true;
            }

            $this->message = 'Pesan atau file tidak boleh kosong.';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message;
    }
}
