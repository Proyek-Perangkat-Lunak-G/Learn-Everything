<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chat;
use App\Services\ChatService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChatValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $sender;
    private User $receiver;
    private ChatService $chatService;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup test users
        $this->sender = User::factory()->create(['role' => 'user']);
        $this->receiver = User::factory()->create(['role' => 'tutor']);
        
        // Setup service
        $this->chatService = new ChatService();

        // Setup storage disk
        Storage::fake('public');
    }

    // ========== BOUNDARY VALUE ANALYSIS: MESSAGE LENGTH ==========

    /**
     * BVA-M-1: Empty message without attachment should FAIL
     * 
     * @test
     */
    public function test_bva_m1_empty_message_without_attachment_fails()
    {
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.general', 'Pesan atau file tidak boleh kosong.');
        
        // Verify no chat created
        $this->assertDatabaseCount('chats', 0);
    }

    /**
     * BVA-M-2: Single character message should PASS
     * 
     * @test
     */
    public function test_bva_m2_single_character_message_passes()
    {
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'A',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        
        // Verify chat created
        $this->assertDatabaseHas('chats', [
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
            'message' => 'A',
        ]);
    }

    /**
     * BVA-M-3: Medium length message (250 chars) should PASS
     * 
     * @test
     */
    public function test_bva_m3_medium_message_250_chars_passes()
    {
        $message = str_repeat('a', 250);
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => $message,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        
        // Verify message length
        $chat = Chat::first();
        $this->assertEquals(250, strlen($chat->message));
    }

    /**
     * BVA-M-4: Message 499 characters (one below upper boundary) should PASS
     * 
     * @test
     */
    public function test_bva_m4_message_499_chars_passes()
    {
        $message = str_repeat('a', 499);
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => $message,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        
        // Verify exact length
        $chat = Chat::first();
        $this->assertEquals(499, strlen($chat->message));
    }

    /**
     * BVA-M-5: Message 500 characters (upper boundary) should PASS
     * 
     * @test
     */
    public function test_bva_m5_message_500_chars_passes()
    {
        $message = str_repeat('a', 500);
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => $message,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        
        // Verify exact length
        $chat = Chat::first();
        $this->assertEquals(500, strlen($chat->message));
    }

    /**
     * BVA-M-6: Message 501 characters (above upper boundary) should FAIL
     * 
     * @test
     */
    public function test_bva_m6_message_501_chars_fails()
    {
        $message = str_repeat('a', 501);
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => $message,
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.message', 'Pesan maksimal 500 karakter.');
        
        // Verify no chat created
        $this->assertDatabaseCount('chats', 0);
    }

    // ========== BOUNDARY VALUE ANALYSIS: ATTACHMENT SIZE ==========

    /**
     * BVA-A-1: Attachment 1 byte should PASS
     * 
     * @test
     */
    public function test_bva_a1_attachment_1_byte_passes()
    {
        $file = UploadedFile::fake()->create('test.txt', 1); // 1 byte
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'File attachment',
            'attachment' => $file,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.attachment_name', 'test.txt');
        
        // Verify file stored
        Storage::disk('public')->assertExists($response->json('data.attachment'));
    }

    /**
     * BVA-A-2: Attachment 10239 bytes (just below limit) should PASS
     * 
     * @test
     */
    public function test_bva_a2_attachment_10239_bytes_passes()
    {
        $file = UploadedFile::fake()->create('test.pdf', 10239); // 10239 bytes
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'Large file',
            'attachment' => $file,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
    }

    /**
     * BVA-A-3: Attachment 10240 bytes (exact limit) should PASS
     * 
     * @test
     */
    public function test_bva_a3_attachment_10240_bytes_passes()
    {
        $file = UploadedFile::fake()->create('test.zip', 10240); // 10240 bytes
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'Max size file',
            'attachment' => $file,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
    }

    /**
     * BVA-A-4: Attachment 10241 bytes (over limit) should FAIL
     * 
     * @test
     */
    public function test_bva_a4_attachment_10241_bytes_fails()
    {
        $file = UploadedFile::fake()->create('test.bin', 10241); // 10241 bytes (over limit)
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'Oversized file',
            'attachment' => $file,
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.attachment', 'Ukuran file maksimal 10 MB.');
        
        // Verify no file stored
        Storage::disk('public')->assertCount(0);
    }

    // ========== EQUIVALENCE PARTITIONING ==========

    /**
     * EP-1: Valid message (1-500 chars) + No attachment = VALID
     * 
     * @test
     */
    public function test_ep1_valid_message_no_attachment_passes()
    {
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'Halo tutor, saya ingin bertanya',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('chats', 1);
        
        $chat = Chat::first();
        $this->assertEquals('Halo tutor, saya ingin bertanya', $chat->message);
        $this->assertNull($chat->attachment);
    }

    /**
     * EP-2: Empty message + Valid attachment (≤10MB) = VALID
     * 
     * @test
     */
    public function test_ep2_empty_message_valid_attachment_passes()
    {
        $file = UploadedFile::fake()->create('document.pdf', 5240);
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => '',
            'attachment' => $file,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('chats', 1);
        
        $chat = Chat::first();
        $this->assertEquals('', $chat->message);
        $this->assertNotNull($chat->attachment);
    }

    /**
     * EP-3: Valid message (1-500 chars) + Valid attachment (≤10MB) = VALID
     * 
     * @test
     */
    public function test_ep3_valid_message_valid_attachment_passes()
    {
        $file = UploadedFile::fake()->create('report.docx', 8000);
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'Lihat file penting ini',
            'attachment' => $file,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('chats', 1);
        
        $chat = Chat::first();
        $this->assertEquals('Lihat file penting ini', $chat->message);
        $this->assertNotNull($chat->attachment);
    }

    /**
     * EP-4: Empty message + No attachment = INVALID
     * 
     * @test
     */
    public function test_ep4_empty_message_no_attachment_fails()
    {
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.general', 'Pesan atau file tidak boleh kosong.');
        $this->assertDatabaseCount('chats', 0);
    }

    /**
     * EP-5: Over-limit message (>500 chars) + No attachment = INVALID
     * 
     * @test
     */
    public function test_ep5_over_limit_message_no_attachment_fails()
    {
        $message = str_repeat('a', 501);
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => $message,
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.message', 'Pesan maksimal 500 karakter.');
        $this->assertDatabaseCount('chats', 0);
    }

    /**
     * EP-6: Valid message (1-500 chars) + Over-limit attachment (>10MB) = INVALID
     * 
     * @test
     */
    public function test_ep6_valid_message_over_limit_attachment_fails()
    {
        $file = UploadedFile::fake()->create('huge_video.mp4', 11000); // 11MB (over limit)
        
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'Video yang terlalu besar',
            'attachment' => $file,
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.attachment', 'Ukuran file maksimal 10 MB.');
        $this->assertDatabaseCount('chats', 0);
    }

    // ========== WHITESPACE & TRIMMING ==========

    /**
     * Test that whitespace-only messages are trimmed and fail
     * 
     * @test
     */
    public function test_whitespace_only_message_fails()
    {
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => '   ', // Only spaces
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.general', 'Pesan atau file tidak boleh kosong.');
    }

    /**
     * Test that leading/trailing whitespace is trimmed
     * 
     * @test
     */
    public function test_message_whitespace_trimmed()
    {
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => '  Halo tutor  ',
        ]);

        $response->assertStatus(201);
        
        $chat = Chat::first();
        $this->assertEquals('Halo tutor', $chat->message);
    }

    // ========== AUTHORIZATION ==========

    /**
     * Test that admin cannot send chat
     * 
     * @test
     */
    public function test_admin_cannot_send_chat()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'Test message',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that unauthenticated user cannot send chat
     * 
     * @test
     */
    public function test_unauthenticated_user_cannot_send_chat()
    {
        $response = $this->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'Test message',
        ]);

        $response->assertStatus(401);
    }

    // ========== SPECIAL CASES ==========

    /**
     * Test message with unicode characters
     * 
     * @test
     */
    public function test_message_with_unicode_characters()
    {
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => 'Hello! 你好! مرحبا! 🎉',
        ]);

        $response->assertStatus(201);
        
        $chat = Chat::first();
        $this->assertStringContainsString('Hello', $chat->message);
    }

    /**
     * Test message with line breaks
     * 
     * @test
     */
    public function test_message_with_line_breaks()
    {
        $response = $this->actingAs($this->sender)->postJson('/chat/send', [
            'receiver_id' => $this->receiver->id,
            'message' => "Baris pertama\nBaris kedua\nBaris ketiga",
        ]);

        $response->assertStatus(201);
        
        $chat = Chat::first();
        $this->assertStringContainsString("\n", $chat->message);
    }

    // ========== SERVICE LAYER TESTS ==========

    /**
     * Test ChatService::validateChatMessage() method
     * 
     * @test
     */
    public function test_chat_service_validate_message()
    {
        // Valid message
        $result = $this->chatService->validateChatMessage('Halo', null);
        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);

        // Over limit message
        $result = $this->chatService->validateChatMessage(str_repeat('a', 501), null);
        $this->assertFalse($result['valid']);
        $this->assertNotEmpty($result['errors']);
    }

    /**
     * Test ChatService::getBoundaryValueTestData() method
     * 
     * @test
     */
    public function test_get_boundary_value_test_data()
    {
        $data = ChatService::getBoundaryValueTestData();
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('attachment', $data);
        
        // Verify message cases
        $this->assertCount(6, $data['message']);
        
        // Verify attachment cases
        $this->assertCount(4, $data['attachment']);
    }

    /**
     * Test ChatService::getEquivalencePartitionData() method
     * 
     * @test
     */
    public function test_get_equivalence_partition_data()
    {
        $data = ChatService::getEquivalencePartitionData();
        
        $this->assertIsArray($data);
        $this->assertCount(6, $data); // 6 EP cases
    }
}
