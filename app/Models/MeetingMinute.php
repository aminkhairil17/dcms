<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingMinute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'meeting_id',
        'agenda',
        'discussion_points',
        'decisions',
        'action_items',
        'next_meeting_agenda',
        'minute_taker_id',
        'minute_date',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'minute_date' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function meeting(): BelongsTo { return $this->belongsTo(Meeting::class); }
    public function minuteTaker(): BelongsTo { return $this->belongsTo(User::class, 'minute_taker_id'); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
    public function actionItems(): HasMany { return $this->hasMany(MeetingActionItem::class); }
    public function attendees(): HasMany { return $this->hasMany(MeetingMinuteAttendee::class); }
}