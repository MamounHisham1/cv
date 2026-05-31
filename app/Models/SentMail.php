<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SentMail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_email',
        'subject',
        'body',
        'template',
        'status',
        'failed_reason',
    ];

    public const STATUS_SENT = 'sent';

    public const STATUS_PENDING = 'pending';

    public const STATUS_FAILED = 'failed';

    public const TEMPLATE_ANNOUNCEMENT = 'announcement';

    public const TEMPLATE_NOTICE = 'notice';

    public const TEMPLATE_UPDATE = 'update';

    public const TEMPLATE_OPTIONS = [
        self::TEMPLATE_ANNOUNCEMENT => 'Announcement',
        self::TEMPLATE_NOTICE => 'Notice',
        self::TEMPLATE_UPDATE => 'Update',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
