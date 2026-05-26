<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tutor extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'skills', 'hourly_rate', 'rating', 'total_reviews', 
        'document', 'status', 'bank_name', 'bank_account_holder', 'bank_account_number',
        'wallet_pending', 'wallet_available'
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'rating' => 'decimal:2',
            'wallet_pending' => 'decimal:2',
            'wallet_available' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(TutorReview::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(HomeVisitBooking::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(TutorPayment::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(TutorAvailability::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(TutorWithdrawal::class);
    }

    public function updateRating(): void
    {
        $this->rating = $this->reviews()->avg('rating') ?? 0;
        $this->total_reviews = $this->reviews()->count();
        $this->save();
    }
}
