<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TutorPayment extends Model
{
    protected $fillable = ['tutor_id', 'admin_id', 'amount', 'period', 'notes', 'status'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Tutor::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}