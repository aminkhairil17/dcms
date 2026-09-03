<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentDiscussion extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'parent_id',
        'content',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(DocumentDiscussion::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(DocumentDiscussion::class, 'parent_id');
    }
}
