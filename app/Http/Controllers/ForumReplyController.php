<?php

namespace App\Http\Controllers;

use App\Models\ForumThread;
use App\Models\ForumReply;
use App\Models\ForumLike;
use Illuminate\Http\Request;

class ForumReplyController extends Controller
{
    public function store(Request $request, ForumThread $thread)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $thread->replies()->create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Reply posted!');
    }

    public function toggleLike(Request $request, ForumReply $reply)
    {
        $userId = $request->user()->id;
        $like = ForumLike::where('user_id', $userId)->where('forum_reply_id', $reply->id)->first();

        if ($like) {
            $like->delete();
        } else {
            ForumLike::create(['user_id' => $userId, 'forum_reply_id' => $reply->id]);
        }

        return back();
    }
}