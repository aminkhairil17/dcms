<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DocumentNumbering extends Model
{
    protected $fillable = [
        'category_id',
        'company_id',
        'department_id',
        'year',
        'last_number',
    ];

    protected $casts = [
        'year' => 'integer',
        'last_number' => 'integer',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
