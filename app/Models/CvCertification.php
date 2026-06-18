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
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiration_date' => 'date',
        ];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    public function getIsValidAttribute(): bool
    {
        if (! $this->expiration_date) {
            return true;
        }

        return $this->expiration_date->isFuture();
    }
}
