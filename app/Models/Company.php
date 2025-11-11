<?php
// app/Models/Company.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Audit;

class Company extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'code', 'is_active'];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(DocumentCategory::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
