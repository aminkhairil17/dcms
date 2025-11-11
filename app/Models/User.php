<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Audit;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'department_id',
        'unit_id',
        'email_verified_at',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'company_id' => 'integer',
        'department_id' => 'integer',
        'unit_id' => 'integer',
    ];

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the company that owns the user.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the department that owns the user.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the unit that owns the user.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the documents for the user.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the approved documents for the user.
     */
    public function approvedDocuments(): HasMany
    {
        return $this->hasMany(Document::class, 'approver_id');
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    /**
     * Check if user email is verified.
     */
    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Get user's full organizational path.
     */
    public function getOrganizationPathAttribute(): string
    {
        $path = [];

        if ($this->company) {
            $path[] = $this->company->name;
        }

        if ($this->department) {
            $path[] = $this->department->name;
        }

        if ($this->unit) {
            $path[] = $this->unit->name;
        }

        return implode(' → ', $path);
    }
}
