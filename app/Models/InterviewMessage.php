<?php

namespace App\Models;

use Database\Factories\InterviewMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewMessage extends Model
{
    /** @use HasFactory<InterviewMessageFactory> */
    use HasFactory;

    protected $fillable = [
        'interview_session_id',
        'role',
        'content',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(InterviewSession::class, 'interview_session_id');
    }
}
