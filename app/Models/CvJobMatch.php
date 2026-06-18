<?php

namespace App\Models;

use Database\Factories\CvJobMatchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvJobMatch extends Model
{
    /** @use HasFactory<CvJobMatchFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_PROCESSING,
        self::STATUS_COMPLETED,
        self::STATUS_FAILED,
    ];

    protected $fillable = [
        'user_id',
        'cv_id',
        'status',
        'job_description',
        'job_title',
        'compatibility_score',
        'grade',
        'summary',
        'matched_keywords',
        'missing_keywords',
        'gap_analysis',
        'suggestions',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'compatibility_score' => 'integer',
            'matched_keywords' => 'array',
            'missing_keywords' => 'array',
            'gap_analysis' => 'array',
            'suggestions' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }
}
