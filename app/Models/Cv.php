<?php

namespace App\Models;

use Database\Factories\CvFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cv extends Model
{
    /** @use HasFactory<CvFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'template_id',
        'status',
        'personal_info',
        'summary',
        'sort_order',
        'section_order',
    ];

    protected function casts(): array
    {
        return [
            'personal_info' => 'array',
            'section_order' => 'array',
        ];
    }

    public function getSectionOrder(): array
    {
        return $this->section_order ?? [
            'personal',
            'experience',
            'skills',
            'certifications',
            'education',
            'projects',
            'languages',
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

    public function languages(): HasMany
    {
        return $this->hasMany(CvLanguage::class)->orderBy('sort_order');
    }

    public function getFullNameAttribute(): string
    {
        return $this->personal_info['first_name'].' '.$this->personal_info['last_name'];
    }

    public function getAwsCertificationsAttribute()
    {
        return $this->certifications()->where('is_aws_certification', true)->get();
    }

    public function getAwsSkillsAttribute()
    {
        return $this->skills()->where('is_aws_service', true)->get();
    }

    public function toText(): string
    {
        $this->load(['experiences', 'educations', 'skills', 'certifications', 'projects', 'languages']);

        $parts = [];

        $name = trim(($this->personal_info['first_name'] ?? '').' '.($this->personal_info['last_name'] ?? ''));
        if ($name) {
            $parts[] = $name;
        }

        $contacts = array_filter([
            $this->personal_info['email'] ?? null,
            $this->personal_info['phone'] ?? null,
            $this->personal_info['location'] ?? null,
        ]);
        if (! empty($contacts)) {
            $parts[] = implode(' | ', $contacts);
        }

        $links = array_filter([
            isset($this->personal_info['linkedin']) ? 'LinkedIn: '.$this->personal_info['linkedin'] : null,
            isset($this->personal_info['github']) ? 'GitHub: '.$this->personal_info['github'] : null,
            isset($this->personal_info['website']) ? 'Website: '.$this->personal_info['website'] : null,
        ]);
        if (! empty($links)) {
            $parts[] = implode(' | ', $links);
        }

        $parts[] = '';

        if ($this->summary) {
            $parts[] = 'PROFESSIONAL SUMMARY';
            $parts[] = $this->summary;
            $parts[] = '';
        }

        if ($this->experiences->isNotEmpty()) {
            $parts[] = 'WORK EXPERIENCE';
            foreach ($this->experiences as $exp) {
                $line = $exp->title.' at '.$exp->company;
                if ($exp->location) {
                    $line .= ' ('.$exp->location.')';
                }
                $parts[] = $line;

                $dates = [];
                if ($exp->start_date) {
                    $dates[] = $exp->start_date;
                }
                if ($exp->is_current) {
                    $dates[] = 'Present';
                } elseif ($exp->end_date) {
                    $dates[] = $exp->end_date;
                }
                if (! empty($dates)) {
                    $parts[] = implode(' - ', $dates);
                }

                if ($exp->description) {
                    $parts[] = $exp->description;
                }

                if (! empty($exp->achievements)) {
                    foreach ($exp->achievements as $achievement) {
                        if (is_string($achievement) && trim($achievement)) {
                            $parts[] = '- '.$achievement;
                        }
                    }
                }

                if (! empty($exp->technologies)) {
                    $techs = is_array($exp->technologies) ? implode(', ', $exp->technologies) : (string) $exp->technologies;
                    if (trim($techs)) {
                        $parts[] = 'Technologies: '.$techs;
                    }
                }

                $parts[] = '';
            }
        }

        if ($this->skills->isNotEmpty()) {
            $parts[] = 'SKILLS';
            foreach ($this->skills as $skill) {
                $line = $skill->name;
                if ($skill->category) {
                    $line .= ' ('.$skill->category.')';
                }
                if ($skill->level) {
                    $line .= ' - '.ucfirst($skill->level);
                }
                $parts[] = '- '.$line;
            }
            $parts[] = '';
        }

        if ($this->educations->isNotEmpty()) {
            $parts[] = 'EDUCATION';
            foreach ($this->educations as $edu) {
                $line = $edu->degree;
                if ($edu->field_of_study) {
                    $line .= ' in '.$edu->field_of_study;
                }
                $parts[] = $line;
                $parts[] = $edu->institution.($edu->location ? ', '.$edu->location : '');

                $dates = [];
                if ($edu->start_date) {
                    $dates[] = $edu->start_date;
                }
                if ($edu->is_current) {
                    $dates[] = 'Present';
                } elseif ($edu->end_date) {
                    $dates[] = $edu->end_date;
                }
                if (! empty($dates)) {
                    $parts[] = implode(' - ', $dates);
                }

                if ($edu->description) {
                    $parts[] = $edu->description;
                }
                $parts[] = '';
            }
        }

        if ($this->certifications->isNotEmpty()) {
            $parts[] = 'CERTIFICATIONS';
            foreach ($this->certifications as $cert) {
                $line = $cert->name;
                if ($cert->issuing_organization) {
                    $line .= ' - '.$cert->issuing_organization;
                }
                if ($cert->issue_date) {
                    $line .= ' ('.$cert->issue_date.')';
                }
                $parts[] = '- '.$line;
            }
            $parts[] = '';
        }

        if ($this->projects->isNotEmpty()) {
            $parts[] = 'PROJECTS';
            foreach ($this->projects as $proj) {
                $parts[] = $proj->name;
                if ($proj->description) {
                    $parts[] = $proj->description;
                }
                if (! empty($proj->key_achievements)) {
                    foreach ($proj->key_achievements as $achievement) {
                        if (is_string($achievement) && trim($achievement)) {
                            $parts[] = '- '.$achievement;
                        }
                    }
                }
                if (! empty($proj->aws_services_used)) {
                    $services = is_array($proj->aws_services_used) ? implode(', ', $proj->aws_services_used) : (string) $proj->aws_services_used;
                    if (trim($services)) {
                        $parts[] = 'Services: '.$services;
                    }
                }
                $parts[] = '';
            }
        }

        if ($this->languages->isNotEmpty()) {
            $parts[] = 'LANGUAGES';
            foreach ($this->languages as $lang) {
                $parts[] = '- '.$lang->language.' ('.ucfirst($lang->proficiency).')';
            }
        }

        return implode("\n", $parts);
    }
}
