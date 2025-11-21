<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'agenda', 'content', 'file_path', 'date_time', 'location', 'status', 'company_id', 'department_id', 'unit_id', 'created_by'];

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
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function getParticipantNamesAttribute()
    {
        return $this->participants->pluck('name')->implode(', ');
    }
    public function scopeAccess(Builder $query)
    {
        $user = auth()->user();
        return $query->where(function ($q) use ($user) {

            // 1. BYPASS — jika user adalah participant meeting
            $q->whereHas('participants', function ($p) use ($user) {
                $p->where('users.id', $user->id);
            })

                // 2. Jika bukan participant → cek hak akses default berdasarkan role
                ->orWhere(function ($q2) use ($user) {

                    if ($user->hasRole('super_admin')) {
                        // Direktur akses semua meeting dalam perusahaan
                        $q2;
                    }
                    if ($user->hasRole('direktur')) {
                        // Direktur akses semua meeting dalam perusahaan
                        $q2->where('company_id', $user->company_id);
                    } elseif ($user->hasRole('manager')) {
                        // Manager akses semua meeting dalam departemen
                        $q2->where('department_id', $user->department_id);
                    } else {
                        // Staff akses meeting unit-nya sendiri
                        $q2->where('unit_id', $user->unit_id);
                    }
                });
        });
    }
}
