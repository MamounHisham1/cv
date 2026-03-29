<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvProject extends Model
{
    /** @use HasFactory<\Database\Factories\CvProjectFactory> */
    use HasFactory;

    protected $table = 'cv_projects';

    protected $fillable = [
        'cv_id',
        'name',
        'description',
        'aws_services_used',
        'architecture_type',
        'key_achievements',
        'project_url',
        'github_url',
        'start_date',
        'end_date',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'aws_services_used' => 'array',
            'key_achievements' => 'array',
        ];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }

    public const ARCHITECTURE_TYPES = [
        'serverless' => 'Serverless',
        'microservices' => 'Microservices',
        'monolithic' => 'Monolithic',
        'event-driven' => 'Event-Driven',
        'multi-tier' => 'Multi-Tier',
        'hybrid' => 'Hybrid Cloud',
        'containerized' => 'Containerized',
    ];
}
