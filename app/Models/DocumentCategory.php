<?php
// app/Models/DocumentCategory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentCategory extends Model
{
    protected $fillable = ['name', 'prefix', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
