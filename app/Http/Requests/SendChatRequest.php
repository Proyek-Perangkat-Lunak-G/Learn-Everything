<?php

namespace App\Http\Requests;

use App\Rules\ValidChatMessageOrAttachment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi pengiriman pesan chat
 * 
 * Business Rules:
 * 1. Message length: 1-500 karakter (atau kosong jika ada attachment)
 * 2. Attachment: max 10MB (10240 KB)
 * 3. Kombinasi: (message 1-500 + optional attachment) OR (empty message + mandatory attachment)
 */
class SendChatRequest extends FormRequest
{
    /**
     * Tentukan jika user berwenang membuat request ini
     */
    public function authorize(): bool
    {
        // User harus authenticated dan bukan admin
        return auth()->check() && !auth()->user()->isAdmin();
    }

    /**
     * Dapatkan validation rules
     */
    public function rules(): array
    {
        return [
            'receiver_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::notIn([auth()->id()]), // Bisa chat dengan diri sendiri atau tidak (sesuai requirement)
            ],
            
            // MESSAGE VALIDATION
            'message' => [
                'nullable',
                'string',
                'max:500', // BVA: max 500 karakter (boundary)
                'min:1',   // Jika ada, minimal 1 karakter
                new ValidChatMessageOrAttachment(), // Custom rule untuk kombinasi
            ],
            
            // ATTACHMENT VALIDATION
            'attachment' => [
                'nullable',
                'file',
                'max:10240', // BVA: max 10MB (10240 KB)
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif,zip,txt', // Whitelist format file
            ],
        ];
    }

    /**
     * Custom messages untuk error
     */
    public function messages(): array
    {
        return [
            // Message validation messages
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.string' => 'Pesan harus berupa teks.',
            'message.max' => 'Pesan maksimal 500 karakter.',
            'message.min' => 'Pesan minimal 1 karakter.',

            // Receiver validation messages
            'receiver_id.required' => 'Penerima pesan harus dipilih.',
            'receiver_id.integer' => 'ID penerima tidak valid.',
            'receiver_id.exists' => 'Penerima pesan tidak ditemukan.',
            'receiver_id.not_in' => 'Anda tidak dapat mengirim pesan kepada diri sendiri.',

            // Attachment validation messages
            'attachment.file' => 'File harus berupa file yang valid.',
            'attachment.max' => 'Ukuran file maksimal 10 MB.',
            'attachment.mimes' => 'Format file tidak didukung. Gunakan: pdf, doc, docx, xls, xlsx, jpg, png, gif, zip, txt.',
        ];
    }

    /**
     * Prepare data untuk divalidasi
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace dari message
        $this->merge([
            'message' => trim($this->input('message', '')),
        ]);
    }
}
