<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'plan',
        'monthly_credits_reset_at',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'integer',
            'monthly_credits_reset_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $balance) {
            $balance->monthly_credits_reset_at = $balance->monthly_credits_reset_at ?? now();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class, 'user_id', 'user_id');
    }
}
