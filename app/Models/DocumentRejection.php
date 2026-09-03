<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRejection extends Model
{
    protected $fillable = [
        'document_id',
        'user_id',
        'role',
        'notes',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
