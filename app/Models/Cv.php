<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cv extends Model
{
    /** @use HasFactory<\Database\Factories\CvFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'template_id',
        'status',
        'personal_info',
        'summary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'personal_info' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(CvExperience::class)->orderBy('sort_order')->orderByDesc('start_date');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(CvEducation::class)->orderBy('sort_order')->orderByDesc('start_date');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(CvSkill::class)->orderBy('sort_order');
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(CvCertification::class)->orderBy('sort_order')->orderByDesc('issue_date');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(CvProject::class)->orderBy('sort_order');
    }

    public function getFullNameAttribute(): string
    {
        return $this->personal_info['first_name'] . ' ' . $this->personal_info['last_name'];
    }

    public function getAwsCertificationsAttribute()
    {
        return $this->certifications()->where('is_aws_certification', true)->get();
    }

    public function getAwsSkillsAttribute()
    {
        return $this->skills()->where('is_aws_service', true)->get();
    }
}
