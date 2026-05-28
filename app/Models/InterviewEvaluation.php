<?php

namespace App\Models;

use Database\Factories\InterviewEvaluationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewEvaluation extends Model
{
    /** @use HasFactory<InterviewEvaluationFactory> */
    use HasFactory;

    protected $fillable = [
        'interview_session_id',
        'overall_score',
        'grade',
        'summary',
        'criteria',
        'strengths',
        'improvements',
    ];

    protected function casts(): array
    {
        return [
            'overall_score' => 'integer',
            'criteria' => 'array',
            'strengths' => 'array',
            'improvements' => 'array',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(InterviewSession::class, 'interview_session_id');
    }
}
