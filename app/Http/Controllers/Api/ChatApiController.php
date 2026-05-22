<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatApiController extends Controller
{
    public function messages(Request $request, User $user)
    {
        $authUser = $request->user();

        $messages = Chat::where(function ($q) use ($authUser, $user) {
            $q->where('sender_id', $authUser->id)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($authUser, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $authUser->id);
        })->orderBy('created_at')->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        $chat = Chat::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        $chat->load('sender');

        return response()->json($chat, 201);
    }
}