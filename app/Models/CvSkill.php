<?php

namespace App\Models;

use App\Cv\IndustryPacks\GenericPack;
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
        'sort_order',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    /**
     * Skill proficiency levels (industry-neutral).
     */
    public const LEVELS = [
        'beginner' => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced' => 'Advanced',
        'expert' => 'Expert',
    ];

    /**
     * Canonical industry-neutral skill categories. Single source of truth
     * mirrored by {@see GenericPack::skillCategories()}.
     * Kept as a constant so AI-tool enums and validators can read it
     * synchronously. Specialized packs (cloud, etc.) extend this list.
     */
    public const CATEGORIES = [
        'general' => 'General',
        'technical' => 'Technical Skills',
        'software' => 'Software & Tools',
        'industry' => 'Industry Knowledge',
        'soft' => 'Soft Skills',
    ];
}
