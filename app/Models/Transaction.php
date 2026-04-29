<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 'address_id', 'shipping_address', 'shipping_phone',
        'transaction_code', 'total_amount',
        'status', 'payment_method', 'items',
        'type', 'notes', 'payment_proof',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'items' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
