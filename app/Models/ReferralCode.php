<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ReferralCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $referralCode) {
            if (empty($referralCode->code)) {
                $referralCode->code = self::generateUniqueCode();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateUniqueCode(): string
    {
        return Str::upper(Str::random(6));
    }
}
