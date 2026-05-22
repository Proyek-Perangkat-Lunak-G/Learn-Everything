<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Module extends Model
{
    protected $fillable = ['course_id', 'title', 'content', 'video_url', 'video_path', 'order'];

    /**
     * Returns the best embed-ready URL for the video.
     * Handles YouTube, Vimeo, local uploads, and raw URLs.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        // Local uploaded video takes priority
        if ($this->video_path) {
            return Storage::url($this->video_path);
        }

        if (!$this->video_url) {
            return null;
        }

        // YouTube: watch, short, or already embed URL
        if (preg_match(
            '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            $this->video_url,
            $m
        )) {
            return 'https://www.youtube.com/embed/' . $m[1] . '?rel=0&modestbranding=1';
        }

        // Vimeo
        if (preg_match('/(?:vimeo\.com\/)(\d+)/', $this->video_url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return $this->video_url;
    }

    public function getIsLocalVideoAttribute(): bool
    {
        return (bool) $this->video_path;
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(ModuleProgress::class);
    }
}
