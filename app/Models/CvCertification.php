<?php

namespace App\Models;

use Database\Factories\CvCertificationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvCertification extends Model
{
    /** @use HasFactory<CvCertificationFactory> */
    use HasFactory;

    protected $table = 'cv_certifications';

    protected $fillable = [
        'cv_id',
        'name',
        'issuing_organization',
        'issue_date',
        'expiration_date',
        'credential_id',
        'credential_url',
        'is_aws_certification',
        'aws_level',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiration_date' => 'date',
            'is_aws_certification' => 'boolean',
        ];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    public const AWS_LEVELS = [
        'foundational' => 'Foundational',
        'associate' => 'Associate',
        'professional' => 'Professional',
        'specialty' => 'Specialty',
    ];

    public function getIsValidAttribute(): bool
    {
        if (! $this->expiration_date) {
            return true;
        }

        return $this->expiration_date->isFuture();
    }
}
