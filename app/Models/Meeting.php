<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'meeting_date',
        'location',
        'meeting_code',
        'company_id',
        'department_id',
        'unit_id',
        'created_by',
        'status',
        'meeting_type',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
    ];

    // Generate unique meeting code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($meeting) {
            if (auth()->check()) {
                $meeting->created_by = auth()->id();
            }
            
            if (empty($meeting->meeting_code)) {
                $meeting->meeting_code = 'MTG-' . strtoupper(uniqid());
            }
        });
    }

    // Relationships
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function unit(): BelongsTo { return $this->belongsTo(Unit::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function invitations(): HasMany { return $this->hasMany(MeetingInvitation::class); }
    public function minutes(): HasMany { return $this->hasMany(MeetingMinute::class); }
    
    // Scope for upcoming meetings
    public function scopeUpcoming($query)
    {
        return $query->where('meeting_date', '>=', now())
                    ->whereIn('status', ['scheduled', 'ongoing']);
    }
    
    // Scope for user's meetings
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('invitations', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}