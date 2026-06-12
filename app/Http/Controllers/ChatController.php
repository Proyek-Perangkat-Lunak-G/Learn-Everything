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

    public function send(Request $request)
    {
        // FIXED: Mengubah batasan pesan teks menjadi maksimal 500 karakter
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'nullable|string|max:500', 
            'attachment'  => 'nullable|file|max:10240', // Maksimal 10MB (10240 KB)
        ]);

        // Must have message or attachment
        if (!$request->filled('message') && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Pesan atau lampiran diperlukan.'], 422);
        }

        $attachmentPath = null;
        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('chat-attachments', 'public');
        }

        $chat = Chat::create([
            'sender_id'       => $request->user()->id,
            'receiver_id'     => $request->receiver_id,
            'message'         => $request->message ?? '',
            'attachment'      => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        return response()->json(array_merge($chat->toArray(), [
            'attachment_url'  => $attachmentPath ? asset('storage/' . $attachmentPath) : null,
        ]), 201);
    }
}