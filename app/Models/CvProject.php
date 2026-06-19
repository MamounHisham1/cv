<?php

namespace App\Models;

use App\Casts\JsonArray;
use Database\Factories\CvProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvProject extends Model
{
    /** @use HasFactory<CvProjectFactory> */
    use HasFactory;

    protected $table = 'cv_projects';

    protected $fillable = [
        'cv_id',
        'name',
        'description',
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
            'key_achievements' => JsonArray::class,
        ];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
