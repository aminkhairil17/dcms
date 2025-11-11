<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingActionItem extends Model
{
    protected $fillable = [
        'meeting_minute_id',
        'description',
        'assigned_to',
        'due_date',
        'status',
        'completion_notes',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function minute(): BelongsTo { return $this->belongsTo(MeetingMinute::class, 'meeting_minute_id'); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
}