<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingMinuteAttendee extends Model
{
    protected $fillable = [
        'meeting_minute_id',
        'user_id',
        'attended',
        'notes',
    ];

    protected $casts = [
        'attended' => 'boolean',
    ];

    // Relationships
    public function minute(): BelongsTo { return $this->belongsTo(MeetingMinute::class, 'meeting_minute_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}