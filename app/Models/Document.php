<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Company;
use App\Models\Department;
use App\Models\DocumentCategory;
use App\Models\User;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Audit;


class Document extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'content', // Tambahkan
        'document_type', // Tambahkan
        'generated_file_path', // Tambahkan
        'company_id',
        'department_id',
        'unit_id',
        'category_id',
        'user_id',
        'status',
        'confidential_level',
    ];

    protected $casts = [
        'document_type' => 'string',
    ];

    // Auto-set document type berdasarkan input
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            // Set user_id ke current user
            if (auth()->check()) {
                $document->user_id = auth()->id();
            }

            // Auto-determine document type
            if ($document->file_path && !$document->content) {
                $document->document_type = 'file';
            } elseif ($document->content && !$document->file_path) {
                $document->document_type = 'form';
            } elseif ($document->content && $document->file_path) {
                $document->document_type = 'hybrid';
            }

            // Generate file dari content jika type form/hybrid
            if (in_array($document->document_type, ['form', 'hybrid']) && $document->content) {
                $document->generateFileFromContent();
            }

            // Set file information jika ada file
            if ($document->file_path) {
                $document->setFileInformation();
            }
        });

        static::updating(function ($document) {
            // Regenerate file jika content berubah
            if ($document->isDirty('content') && in_array($document->document_type, ['form', 'hybrid'])) {
                $document->generateFileFromContent();
            }

            // Update file information jika file_path berubah
            if ($document->isDirty('file_path')) {
                $document->setFileInformation();
            }
        });
    }

    // Generate file dari content text
    public function generateFileFromContent()
    {
        if (!$this->content) return;

        $filename = 'document_' . $this->id . '_' . time() . '.html';
        $filePath = 'documents/generated/' . $filename;

        // Format content sebagai HTML
        $htmlContent = $this->formatContentAsHtml();

        // Simpan content ke file
        \Storage::disk('documents')->put($filePath, $htmlContent);

        $this->generated_file_path = $filePath;

        // Untuk document type 'form', set file_path ke generated file
        if ($this->document_type === 'form') {
            $this->file_path = $filePath;
            $this->file_name = $filename;
            $this->setFileInformation();
        }
    }

    // Format content sebagai HTML
    private function formatContentAsHtml(): string
    {
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>{$this->title}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; line-height: 1.6; }
                .header { border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                .title { font-size: 24px; font-weight: bold; color: #333; }
                .meta { color: #666; font-size: 14px; margin-top: 10px; }
                .content { margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <div class='title'>{$this->title}</div>
                <div class='meta'>
                    Created: " . now()->format('d M Y H:i') . " | 
                    Category: {$this->category->name} |
                    Confidential: {$this->confidential_level}
                </div>
            </div>
            <div class='content'>
                " . nl2br(e($this->content)) . "
            </div>
        </body>
        </html>";

        return $html;
    }

    // Existing method untuk file information
    public function setFileInformation()
    {
        $filePath = storage_path('app/documents/' . $this->file_path);

        if (file_exists($filePath)) {
            $this->file_size = $this->formatFileSize(filesize($filePath));
        }
    }

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

    // Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'confidential_level', 'document_type'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Document {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logExcept(['file_size', 'updated_at']);
    }

    // Relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isFileDocument(): bool
    {
        return $this->document_type === 'file';
    }

    public function isFormDocument(): bool
    {
        return $this->document_type === 'form';
    }

    public function isHybridDocument(): bool
    {
        return $this->document_type === 'hybrid';
    }
}
