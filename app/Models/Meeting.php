<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'agenda', 'date_time', 'location', 'status', 'created_by'];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meeting_participants')->withPivot('attendance')->withTimestamps();
    }

    public function minutes()
    {
        return $this->hasMany(MeetingMinute::class);
    }

    public function getParticipantNamesAttribute()
    {
        return $this->participants->pluck('name')->implode(', ');
    }
}
