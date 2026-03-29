<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvExperience extends Model
{
    /** @use HasFactory<\Database\Factories\CvExperienceFactory> */
    use HasFactory;

    protected $table = 'cv_experiences';

    protected $fillable = [
        'cv_id',
        'company',
        'title',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'technologies',
        'achievements',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
            'technologies' => 'array',
            'achievements' => 'array',
        ];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    public function getDurationAttribute(): string
    {
        $start = $this->start_date->format('M Y');
        $end = $this->is_current ? 'Present' : $this->end_date?->format('M Y');

        return "$start - $end";
    }
}
