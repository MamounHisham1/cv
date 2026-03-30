<?php

namespace App\Models;

use Database\Factories\CvLanguageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvLanguage extends Model
{
    /** @use HasFactory<CvLanguageFactory> */
    use HasFactory;

    protected $fillable = [
        'cv_id',
        'language',
        'proficiency',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(Cv::class);
    }
}
