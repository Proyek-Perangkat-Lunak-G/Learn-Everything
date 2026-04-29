<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TutorAvailability extends Model
{
    protected $fillable = [
        'tutor_id', 'date', 'start_time', 'end_time', 'is_available', 'is_booked'
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_available' => 'boolean',
            'is_booked' => 'boolean',
        ];
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function booking(): HasOne
    {
        return $this->hasOne(HomeVisitBooking::class, 'tutor_id', 'tutor_id')
            ->where('date', $this->date)
            ->where('time', $this->start_time);
    }
}