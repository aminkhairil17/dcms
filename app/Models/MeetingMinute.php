<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetingMinute extends Model
{
    use HasFactory;

    protected $fillable = ['meeting_id', 'created_by', 'content', 'action_items', 'decisions', 'mentioned_users'];

    protected $casts = [
        'action_items' => 'array',
        'decisions' => 'array',
        'mentioned_users' => 'array',
    ];

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Method untuk extract mentioned users dari content
    public function getMentionedUsersFromContent()
    {
        preg_match_all('/@\[([^\]]+)\]/', $this->content, $matches);
        return $matches[1] ?? [];
    }
}
