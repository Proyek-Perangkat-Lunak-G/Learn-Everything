<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumLike extends Model
{
    protected $fillable = ['user_id', 'forum_reply_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reply(): BelongsTo
    {
        return $this->belongsTo(ForumReply::class, 'forum_reply_id');
    }
}