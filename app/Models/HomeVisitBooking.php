<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeVisitBooking extends Model
{
    protected $fillable = [
        'user_id', 'tutor_id', 'date', 'time', 'end_time',
        'duration_hours', 'price', 'transaction_id',
        'location', 'status', 'notes', 'is_paid', 'paid_at', 'completed_at'
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'paid_at' => 'datetime',
            'completed_at' => 'datetime',
            'is_paid' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}