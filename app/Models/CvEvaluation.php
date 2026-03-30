<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'overall_score',
        'grade',
        'summary',
        'criteria',
        'top_strengths',
        'critical_improvements',
        'cv_text',
    ];

    protected $casts = [
        'criteria' => 'array',
        'top_strengths' => 'array',
        'critical_improvements' => 'array',
    ];

    /**
     * Get the user that owns this evaluation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
