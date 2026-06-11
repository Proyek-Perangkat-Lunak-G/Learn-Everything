<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * ChatService
 * 
 * Service class untuk menangani business logic chat
 * Termasuk validasi, penyimpanan file, dan recording pesan
 */
class ChatService
{
    // Constants untuk boundary values
    const MESSAGE_MIN_LENGTH = 1;
    const MESSAGE_MAX_LENGTH = 500;
    const ATTACHMENT_MAX_SIZE = 10240; // 10MB dalam KB

    /**
     * Validasi pesan chat sebelum disimpan
     * 
     * @param string $message
     * @param UploadedFile|null $attachment
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validateChatMessage(string $message = '', ?UploadedFile $attachment = null): array
    {
        $errors = [];

        // Trim message
        $message = trim($message);
        $messageLength = strlen($message);

        // RULE 1: Validasi panjang message
        if (!empty($message)) {
            if ($messageLength < self::MESSAGE_MIN_LENGTH) {
                $errors['message'] = 'Pesan minimal ' . self::MESSAGE_MIN_LENGTH . ' karakter.';
            }
            if ($messageLength > self::MESSAGE_MAX_LENGTH) {
                $errors['message'] = 'Pesan maksimal ' . self::MESSAGE_MAX_LENGTH . ' karakter.';
            }
        }

        // RULE 2: Validasi attachment
        if ($attachment !== null) {
            if (!$attachment->isValid()) {
                $errors['attachment'] = 'File tidak valid.';
            }

            $fileSizeKB = $attachment->getSize() / 1024;
            if ($fileSizeKB > self::ATTACHMENT_MAX_SIZE) {
                $errors['attachment'] = 'Ukuran file maksimal ' . self::ATTACHMENT_MAX_SIZE . ' MB.';
            }

            // Validasi tipe file
            $allowedMimes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'txt'];
            $extension = strtolower($attachment->getClientOriginalExtension());
            if (!in_array($extension, $allowedMimes)) {
                $errors['attachment'] = 'Tipe file tidak didukung.';
            }
        }

        // RULE 3: Kombinasi pesan dan attachment (Equivalence Partitioning)
        if (empty($message) && $attachment === null) {
            $errors['general'] = 'Pesan atau file tidak boleh kosong.';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Simpan pesan chat
     * 
     * @param User $sender
     * @param User $receiver
     * @param string $message
     * @param UploadedFile|null $attachment
     * @return Chat|null
     */
    public function saveChat(User $sender, User $receiver, string $message = '', ?UploadedFile $attachment = null): ?Chat
    {
        try {
            return DB::transaction(function () use ($sender, $receiver, $message, $attachment) {
                $attachmentPath = null;
                $attachmentName = null;

                // Simpan attachment jika ada
                if ($attachment !== null) {
                    $attachmentPath = $attachment->store('chat-attachments', 'public');
                    $attachmentName = $attachment->getClientOriginalName();

                    Log::info('Chat attachment saved', [
                        'sender_id' => $sender->id,
                        'receiver_id' => $receiver->id,
                        'attachment_path' => $attachmentPath,
                        'attachment_name' => $attachmentName,
                    ]);
                }

                // Simpan pesan
                $chat = Chat::create([
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'message' => trim($message),
                    'attachment' => $attachmentPath,
                    'attachment_name' => $attachmentName,
                    'is_read' => false,
                ]);

                Log::info('Chat message saved', [
                    'chat_id' => $chat->id,
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'message_length' => strlen($message),
                ]);

                return $chat;
            });
        } catch (\Exception $e) {
            Log::error('Error saving chat', [
                'error' => $e->getMessage(),
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
            ]);

            return null;
        }
    }

    /**
     * Process chat message dengan validasi lengkap
     * 
     * @param User $sender
     * @param User $receiver
     * @param string $message
     * @param UploadedFile|null $attachment
     * @return array ['success' => bool, 'message' => string, 'data' => Chat|null, 'errors' => array]
     */
    public function processChatMessage(
        User $sender,
        User $receiver,
        string $message = '',
        ?UploadedFile $attachment = null
    ): array {
        // Validasi
        $validation = $this->validateChatMessage($message, $attachment);

        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => 'Validasi gagal',
                'data' => null,
                'errors' => $validation['errors'],
            ];
        }

        // Simpan pesan
        $chat = $this->saveChat($sender, $receiver, $message, $attachment);

        if (!$chat) {
            return [
                'success' => false,
                'message' => 'Gagal menyimpan pesan',
                'data' => null,
                'errors' => ['general' => 'Terjadi kesalahan saat menyimpan pesan.'],
            ];
        }

        return [
            'success' => true,
            'message' => 'Pesan berhasil dikirim',
            'data' => $chat,
            'errors' => [],
        ];
    }

    /**
     * Get boundary value test data
     * Untuk keperluan testing
     */
    public static function getBoundaryValueTestData(): array
    {
        return [
            'message_length' => [
                'below_lower' => ['value' => '', 'valid' => false, 'reason' => 'empty'],
                'lower_boundary' => ['value' => 'a', 'valid' => true, 'reason' => '1 char'],
                'typical_valid' => ['value' => str_repeat('a', 250), 'valid' => true, 'reason' => '250 chars'],
                'one_below_upper' => ['value' => str_repeat('a', 499), 'valid' => true, 'reason' => '499 chars'],
                'upper_boundary' => ['value' => str_repeat('a', 500), 'valid' => true, 'reason' => '500 chars'],
                'above_upper' => ['value' => str_repeat('a', 501), 'valid' => false, 'reason' => '501 chars (over limit)'],
            ],
            'attachment_size' => [
                'lower_boundary' => ['size' => 1, 'valid' => true, 'reason' => '1 byte'],
                'below_upper' => ['size' => 10239, 'valid' => true, 'reason' => '10239 bytes'],
                'upper_boundary' => ['size' => 10240, 'valid' => true, 'reason' => '10240 bytes (10MB)'],
                'above_upper' => ['size' => 10241, 'valid' => false, 'reason' => '10241 bytes (over 10MB)'],
            ],
        ];
    }

    /**
     * Get equivalence partition test data
     */
    public static function getEquivalencePartitionData(): array
    {
        return [
            'ep_1' => [
                'description' => 'Valid message (1-500 chars) + No attachment',
                'message' => 'Halo tutor, saya ingin bertanya tentang pelajaran',
                'has_attachment' => false,
                'expected' => 'VALID',
            ],
            'ep_2' => [
                'description' => 'Empty message + Valid attachment (<=10MB)',
                'message' => '',
                'has_attachment' => true,
                'attachment_size' => 5000, // 5MB
                'expected' => 'VALID',
            ],
            'ep_3' => [
                'description' => 'Valid message (1-500 chars) + Valid attachment (<=10MB)',
                'message' => 'Lihat file ini',
                'has_attachment' => true,
                'attachment_size' => 8000, // 8MB
                'expected' => 'VALID',
            ],
            'ep_4' => [
                'description' => 'Empty message + No attachment',
                'message' => '',
                'has_attachment' => false,
                'expected' => 'INVALID',
            ],
            'ep_5' => [
                'description' => 'Invalid message (>500 chars) + No attachment',
                'message' => str_repeat('a', 501),
                'has_attachment' => false,
                'expected' => 'INVALID',
            ],
            'ep_6' => [
                'description' => 'Valid message + Invalid attachment (>10MB)',
                'message' => 'File terlalu besar',
                'has_attachment' => true,
                'attachment_size' => 11000, // 11MB
                'expected' => 'INVALID',
            ],
        ];
    }
}
