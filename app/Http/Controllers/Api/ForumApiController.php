<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumThread;
use App\Models\ForumReply;
use App\Models\ForumLike;
use Illuminate\Http\Request;

class ForumApiController extends Controller
{
    public function index(Request $request)
    {
        $threads = ForumThread::with(['user', 'replies'])
            ->withCount('replies')
            ->latest()
            ->paginate(15);

        return response()->json($threads);
    }

    public function show(ForumThread $thread)
    {
        $thread->load(['user', 'replies.user', 'replies.likes']);
        return response()->json($thread);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $thread = ForumThread::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        $thread->load('user');

        return response()->json($thread, 201);
    }

    public function reply(Request $request, ForumThread $thread)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $reply = ForumReply::create([
            'forum_thread_id' => $thread->id,
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        $reply->load('user');

        return response()->json($reply, 201);
    }

    public function toggleLike(Request $request, ForumReply $reply)
    {
        $user = $request->user();

        $like = ForumLike::where('user_id', $user->id)
            ->where('forum_reply_id', $reply->id)
            ->first();

        if ($like) {
            $like->delete();
            return response()->json(['liked' => false, 'count' => $reply->likes()->count()]);
        }

        ForumLike::create([
            'user_id' => $user->id,
            'forum_reply_id' => $reply->id,
        ]);

        return response()->json(['liked' => true, 'count' => $reply->likes()->count()]);
    }
}
