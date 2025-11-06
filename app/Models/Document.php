<?php
// app/Models/Document.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'file_path', 'file_name', 'file_size', 'version',
        'company_id', 'department_id', 'unit_id', 'category_id', 'user_id',
        'status', 'confidential_level', 'approver_id', 'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Automatic file information and user assignment
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            // Set user_id to current user
            if (auth()->check()) {
                $document->user_id = auth()->id();
            }

            // Set file information if file_path is set
            if ($document->file_path) {
                $document->setFileInformation();
            }
        });

        static::updating(function ($document) {
            // Update file information if file_path changed
            if ($document->isDirty('file_path')) {
                $document->setFileInformation();
            }
        });
    }

    // Method to set file information
    public function setFileInformation()
    {
        $filePath = storage_path('app/documents/' . $this->file_path);
        
        if (file_exists($filePath)) {
            $this->file_size = $this->formatFileSize(filesize($filePath));
        }
    }

    // Format file size to human readable
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    // Relationships
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function unit(): BelongsTo { return $this->belongsTo(Unit::class); }
    public function category(): BelongsTo { return $this->belongsTo(DocumentCategory::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approver_id'); }
}