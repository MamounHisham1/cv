<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VfcashPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_number',
        'type',
        'item_key',
        'credits_granted',
        'amount_egp',
        'customer_phone',
        'status',
        'vfcash_payment_id',
        'source',
        'metadata',
        'expires_at',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'credits_granted' => 'integer',
            'amount_egp' => 'decimal:2',
            'metadata' => 'array',
            'expires_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isPlanUpgrade(): bool
    {
        return $this->type === 'plan_upgrade';
    }

    public function isCreditTopup(): bool
    {
        return $this->type === 'credit_topup';
    }
}
