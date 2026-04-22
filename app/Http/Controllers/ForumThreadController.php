<?php

namespace App\Http\Controllers;

use App\Models\ForumThread;
use App\Http\Requests\StoreForumThreadRequest;
use Illuminate\Http\Request;

class ForumThreadController extends Controller
{
    public function index()
    {
        $threads = ForumThread::with('user')->withCount('replies')->latest()->paginate(15);

        return view('forum.index', compact('threads'));
    }

    public function create()
    {
        return view('forum.create');
    }

    public function store(StoreForumThreadRequest $request)
    {
        ForumThread::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('forum.index')->with('success', 'Thread created!');
    }

    public function show(ForumThread $thread)
    {
        $thread->load(['user', 'replies.user', 'replies.likes']);

        return view('forum.show', compact('thread'));
    }
}
