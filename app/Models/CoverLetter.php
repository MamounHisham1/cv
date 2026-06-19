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

    public const STATUS_DRAFT = 'draft';

    public const STATUS_GENERATING = 'generating';

    public const STATUS_GENERATED = 'generated';

    public const STATUS_FAILED = 'failed';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_GENERATING,
        self::STATUS_GENERATED,
        self::STATUS_FAILED,
    ];

    protected $fillable = [
        'user_id',
        'cv_id',
        'title',
        'body',
        'template_id',
        'metadata',
        'status',
        'job_description',
        'error_message',
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

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isGenerating(): bool
    {
        return $this->status === self::STATUS_GENERATING;
    }

    public function isGenerated(): bool
    {
        return $this->status === self::STATUS_GENERATED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
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
