<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreForumReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|min:1|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Balasan tidak boleh kosong.',
            'content.min' => 'Balasan minimal harus memiliki :min karakter.',
            'content.max' => 'Balasan maksimal :max karakter.',
        ];
    }
}
