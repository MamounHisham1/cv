<?php

namespace App\Models;

use Database\Factories\CvSkillFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvSkill extends Model
{
    /** @use HasFactory<CvSkillFactory> */
    use HasFactory;

    protected $table = 'cv_skills';

    protected $fillable = [
        'cv_id',
        'name',
        'category',
        'level',
        'is_aws_service',
        'aws_metadata',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_aws_service' => 'boolean',
            'aws_metadata' => 'array',
        ];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    public const CATEGORIES = [
        'general' => 'General',
        'cloud' => 'Cloud & AWS',
        'programming' => 'Programming Languages',
        'infrastructure' => 'Infrastructure & DevOps',
        'data' => 'Data & Analytics',
        'security' => 'Security & Compliance',
        'soft' => 'Soft Skills',
    ];

    public const LEVELS = [
        'beginner' => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced' => 'Advanced',
        'expert' => 'Expert',
    ];
}
