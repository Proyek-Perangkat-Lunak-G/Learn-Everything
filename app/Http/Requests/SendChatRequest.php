<?php

namespace App\Http\Requests;

use App\Rules\ValidChatMessageOrAttachment;
use Illuminate\Foundation\Http\FormRequest;

class SendChatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Admin tidak boleh mengirim chat
        return auth()->check() && !auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'receiver_id' => 'required|exists:users,id|different:user_id',
            'message' => 'nullable|string|max:250', // UPDATED: 250 chars max (was 500)
            'attachment' => 'nullable|file|max:10240', // 10MB max = 10240 KB
        ];
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation()
    {
        // Trim whitespace dari message
        $this->merge([
            'message' => trim($this->input('message', '')),
        ]);
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'receiver_id.required' => 'Penerima pesan harus dipilih.',
            'receiver_id.exists' => 'Penerima pesan tidak ditemukan.',
            'receiver_id.different' => 'Anda tidak dapat mengirim pesan kepada diri sendiri.',
            'message.string' => 'Pesan harus berupa teks.',
            'message.max' => 'Pesan maksimal 250 karakter.',
            'attachment.file' => 'File harus berupa file yang valid.',
            'attachment.max' => 'Ukuran file maksimal 10 MB.',
        ];
    }
}
