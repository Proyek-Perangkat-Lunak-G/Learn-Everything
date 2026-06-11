<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendChatRequest;
use App\Models\Chat;
use App\Models\Tutor;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Admin doesn't have chat
        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        // Check if tutor is verified
        if ($user->role === 'tutor' && (!$user->tutor || $user->tutor->status !== 'approved')) {
            return redirect()->route('dashboard')->with('error', 'Anda harus diverifikasi oleh admin sebelum dapat menggunakan fitur chat.');
        }

        // Get unique chat partners (fix grouped orWhere)
        $chatPartnerIds = Chat::where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->get()
            ->map(fn($chat) => $chat->sender_id === $user->id ? $chat->receiver_id : $chat->sender_id)
            ->unique()
            ->values();

        $chatPartners = User::whereIn('id', $chatPartnerIds)->get();

        // Users can chat with approved tutors AND other users (sellers)
        $availableTutors = collect();
        $availableSellers = collect();
        if ($user->role === 'user') {
            $availableTutors = User::where('role', 'tutor')
                ->whereHas('tutor', fn($q) => $q->where('status', 'approved'))
                ->whereNotIn('id', $chatPartnerIds)
                ->get();

            $availableSellers = User::where('role', 'user')
                ->where('id', '!=', $user->id)
                ->whereHas('products', fn($q) => $q->where('is_active', true))
                ->whereNotIn('id', $chatPartnerIds)
                ->get();
        }

        return view('chat.index', compact('chatPartners', 'availableTutors', 'availableSellers'));
    }

    public function show(Request $request, User $receiver)
    {
        $user = $request->user();

        // Admin doesn't have chat
        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        // Check if tutor is verified
        if ($user->role === 'tutor' && (!$user->tutor || $user->tutor->status !== 'approved')) {
            return redirect()->route('dashboard')->with('error', 'Anda harus diverifikasi oleh admin sebelum dapat menggunakan fitur chat.');
        }

        $messages = Chat::where(function ($q) use ($user, $receiver) {
            $q->where('sender_id', $user->id)->where('receiver_id', $receiver->id);
        })->orWhere(function ($q) use ($user, $receiver) {
            $q->where('sender_id', $receiver->id)->where('receiver_id', $user->id);
        })->orderBy('created_at')->get();

        // Mark as read
        Chat::where('sender_id', $receiver->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Get chat partners for sidebar
        $chatPartnerIds = Chat::where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->get()
            ->map(fn($chat) => $chat->sender_id === $user->id ? $chat->receiver_id : $chat->sender_id)
            ->unique()
            ->values();

        $chatPartners = User::whereIn('id', $chatPartnerIds)->get();

        $availableTutors = collect();
        $availableSellers = collect();
        if ($user->role === 'user') {
            $availableTutors = User::where('role', 'tutor')
                ->whereHas('tutor', fn($q) => $q->where('status', 'approved'))
                ->whereNotIn('id', $chatPartnerIds)
                ->get();

            $availableSellers = User::where('role', 'user')
                ->where('id', '!=', $user->id)
                ->whereHas('products', fn($q) => $q->where('is_active', true))
                ->whereNotIn('id', $chatPartnerIds)
                ->get();
        }

        return view('chat.index', compact('chatPartners', 'availableTutors', 'availableSellers', 'messages', 'receiver'));
    }

    /**
     * Mengirim pesan chat dengan validasi lengkap
     * 
     * Business Rules:
     * 1. Message length: 1-500 karakter
     * 2. Attachment: max 10MB
     * 3. Kombinasi: (message 1-500 + optional attachment) OR (empty message + mandatory attachment)
     * 
     * @param SendChatRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(SendChatRequest $request)
    {
        // Request sudah divalidasi oleh SendChatRequest
        $sender = $request->user();
        $receiver = User::findOrFail($request->receiver_id);
        
        // Gunakan ChatService untuk process pesan
        $chatService = new ChatService();
        $result = $chatService->processChatMessage(
            $sender,
            $receiver,
            $request->input('message', ''),
            $request->file('attachment')
        );

        // Jika gagal validasi, return error
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
                'errors' => $result['errors'],
            ], 422);
        }

        // Jika berhasil, return pesan dengan attachment URL
        $chat = $result['data'];
        $attachmentUrl = $chat->attachment ? asset('storage/' . $chat->attachment) : null;

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => array_merge($chat->toArray(), [
                'attachment_url' => $attachmentUrl,
            ]),
        ], 201);
    }
}