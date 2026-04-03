<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvEvaluation extends Model
{
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
        'filename',
        'status',
        'error_message',
        'overall_score',
        'grade',
        'summary',
        'criteria',
        'top_strengths',
        'critical_improvements',
        'cv_text',
    ];

    protected $appends = [
        'display_name',
    ];

    protected $casts = [
        'criteria' => 'array',
        'top_strengths' => 'array',
        'critical_improvements' => 'array',
    ];

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

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeProcessing(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    /**
     * Get a user-friendly display name for the evaluation.
     * Prioritizes the CV title, then cleans up the filename.
     */
    public function getDisplayNameAttribute(): string
    {
        // If linked to a CV, use its title
        if ($this->cv && $this->cv->title) {
            return $this->cv->title;
        }

        // If we have a filename, clean it up
        if ($this->filename) {
            // Check for failed import before cleaning
            if (stripos($this->filename, 'import failed') !== false) {
                return 'Failed Import';
            }

            // Remove file extension
            $name = pathinfo($this->filename, PATHINFO_FILENAME);

            // Replace underscores/hyphens with spaces
            $name = str_replace(['_', '-'], ' ', $name);

            // Convert to title case
            $name = ucwords(strtolower($name));

            return $name ?: 'CV Evaluation';
        }

        return 'Pasted Text';
    }
}
