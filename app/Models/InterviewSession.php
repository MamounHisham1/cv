<?php

namespace App\Models;

use Database\Factories\InterviewSessionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InterviewSession extends Model
{
    /** @use HasFactory<InterviewSessionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cv_id',
        'job_description',
        'status',
        'interview_type',
        'total_questions',
        'duration_seconds',
        'conversation_id',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'total_questions' => 'integer',
            'duration_seconds' => 'integer',
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

    public function messages(): HasMany
    {
        return $this->hasMany(InterviewMessage::class)->orderBy('sort_order');
    }

    public function evaluation(): HasOne
    {
        return $this->hasOne(InterviewEvaluation::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isSetup(): bool
    {
        return $this->status === 'setup';
    }

    /**
     * Format the duration for display.
     */
    public function durationFormatted(): string
    {
        if (! $this->duration_seconds) {
            return '0:00';
        }

        $minutes = intdiv($this->duration_seconds, 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
