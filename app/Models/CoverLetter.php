<?php

namespace App\Models;

use Database\Factories\CoverLetterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoverLetter extends Model
{
    /** @use HasFactory<CoverLetterFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cv_id',
        'title',
        'body',
        'template_id',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The CV this letter was generated from, if any. Standalone letters
     * (no source CV) resolve to null.
     */
    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    /**
     * Sender display name derived from the source CV's personal_info, or
     * falling back to the letter title.
     */
    public function getSenderNameAttribute(): string
    {
        $info = $this->cv?->personal_info ?? [];

        return trim(($info['first_name'] ?? '').' '.($info['last_name'] ?? '')) ?: $this->title;
    }
}
