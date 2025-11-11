<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingInvitation extends Model
{
    protected $fillable = [
        'meeting_id',
        'user_id',
        'status',
        'response_note',
        'responded_at',
        'invitation_sent_at',
        'reminder_sent_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'invitation_sent_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    // Relationships
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Mark invitation as sent
    public function markInvitationSent()
    {
        $this->update(['invitation_sent_at' => now()]);
    }

    // Mark reminder as sent
    public function markReminderSent()
    {
        $this->update(['reminder_sent_at' => now()]);
    }
}
