<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'source',
        'role',
        'content',
        'decision',
        'reason',
        'job_description',
        'structured_data',
    ];

    protected function casts(): array
    {
        return [
            'structured_data' => 'array',
        ];
    }
}
