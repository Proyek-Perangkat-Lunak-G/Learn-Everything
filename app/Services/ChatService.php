<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class ChatService
{
    const MESSAGE_MIN_LENGTH = 1;
    const MESSAGE_MAX_LENGTH = 250; // UPDATED: 250 chars (was 500)
    const ATTACHMENT_MAX_SIZE_KB = 10240; // 10MB

    /**
     * Validate chat message
     */
    public function validateChatMessage(?string $message, ?UploadedFile $attachment): array
    {
        $errors = [];

        // Validation 1: Message length
        if (!empty($message)) {
            $messageLength = strlen($message);

            if ($messageLength < self::MESSAGE_MIN_LENGTH) {
                $errors['message'] = "Pesan minimal " . self::MESSAGE_MIN_LENGTH . " karakter.";
            }

            if ($messageLength > self::MESSAGE_MAX_LENGTH) {
                $errors['message'] = "Pesan maksimal " . self::MESSAGE_MAX_LENGTH . " karakter.";
            }
        }

        // Validation 2: Attachment size
        if ($attachment) {
            $fileSizeKb = $attachment->getSize() / 1024;

            if ($fileSizeKb > self::ATTACHMENT_MAX_SIZE_KB) {
                $errors['attachment'] = "Ukuran file maksimal 10 MB.";
            }
        }

        // Validation 3: At least one of message or attachment
        if (empty($message) && !$attachment) {
            $errors['general'] = "Pesan atau file tidak boleh kosong.";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Save chat message to database with transaction
     */
    public function saveChat(User $sender, User $receiver, string $message, ?UploadedFile $attachment): ?Chat
    {
        return DB::transaction(function () use ($sender, $receiver, $message, $attachment) {
            $attachmentPath = null;
            $attachmentName = null;

            if ($attachment) {
                $attachmentName = $attachment->getClientOriginalName();
                $attachmentPath = $attachment->store('chat-attachments', 'public');

                Log::info("File uploaded: {$attachmentName} -> {$attachmentPath}");
            }

            $chat = Chat::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'message' => $message,
                'attachment' => $attachmentPath,
                'attachment_name' => $attachmentName,
            ]);

            Log::info("Chat saved", [
                'chat_id' => $chat->id,
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'has_attachment' => !empty($attachmentPath),
            ]);

            return $chat;
        });
    }

    /**
     * Process chat message (validate + save)
     */
    public function processChatMessage(
        User $sender,
        User $receiver,
        string $message,
        ?UploadedFile $attachment = null
    ): array {
        // Validate
        $validation = $this->validateChatMessage($message, $attachment);

        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => 'Validasi gagal',
                'data' => null,
                'errors' => $validation['errors'],
            ];
        }

        // Save
        try {
            $chat = $this->saveChat($sender, $receiver, $message, $attachment);

            return [
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'data' => $chat,
                'errors' => [],
            ];
        } catch (\Exception $e) {
            Log::error('Error saving chat', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Gagal menyimpan pesan',
                'data' => null,
                'errors' => ['general' => 'Terjadi kesalahan. Silahkan coba lagi.'],
            ];
        }
    }

    /**
     * Get test data for Boundary Value Analysis
     */
    public static function getBoundaryValueTestData(): array
    {
        return [
            'message' => [
                ['value' => '', 'description' => 'Empty - Below Lower'],
                ['value' => 'a', 'description' => 'Single Char - Lower Boundary'],
                ['value' => str_repeat('a', 125), 'description' => 'Middle (125 chars)'],
                ['value' => str_repeat('a', 249), 'description' => 'One Below Upper (249 chars)'],
                ['value' => str_repeat('a', 250), 'description' => 'Upper Boundary (250 chars)'],
                ['value' => str_repeat('a', 251), 'description' => 'Above Upper (251 chars)'],
            ],
            'attachment' => [
                ['size' => 1, 'description' => 'Lower Boundary (1 byte)'],
                ['size' => 10239 * 1024, 'description' => 'Just Below Upper (10239 KB)'],
                ['size' => 10240 * 1024, 'description' => 'Upper Boundary (10240 KB = 10MB)'],
                ['size' => 10241 * 1024, 'description' => 'Above Upper (10241 KB)'],
            ],
        ];
    }

    /**
     * Get test data for Equivalence Partitioning
     */
    public static function getEquivalencePartitionData(): array
    {
        return [
            [
                'id' => 'EP-1',
                'description' => 'Valid message (1-250) + No attachment',
                'message' => 'Halo tutor',
                'has_attachment' => false,
                'expected' => 'VALID',
            ],
            [
                'id' => 'EP-2',
                'description' => 'Empty message + Valid attachment',
                'message' => '',
                'has_attachment' => true,
                'expected' => 'VALID',
            ],
            [
                'id' => 'EP-3',
                'description' => 'Valid message (1-250) + Valid attachment',
                'message' => 'Lihat file ini',
                'has_attachment' => true,
                'expected' => 'VALID',
            ],
            [
                'id' => 'EP-4',
                'description' => 'Empty message + No attachment',
                'message' => '',
                'has_attachment' => false,
                'expected' => 'INVALID',
            ],
            [
                'id' => 'EP-5',
                'description' => 'Over-limit message (>250) + No attachment',
                'message' => str_repeat('a', 251),
                'has_attachment' => false,
                'expected' => 'INVALID',
            ],
            [
                'id' => 'EP-6',
                'description' => 'Valid message (1-250) + Invalid attachment (>10MB)',
                'message' => 'File terlalu besar',
                'has_attachment' => true,
                'expected' => 'INVALID',
            ],
        ];
    }
}
