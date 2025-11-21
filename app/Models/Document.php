<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Document extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'title',
        'code_number',
        'description',
        'file_path',
        'file_name',
        'content',
        'document_type',
        'generated_file_path',
        'company_id',
        'department_id',
        'unit_id',
        'category_id',
        'user_id',
        'status',
        'version', // Tambahkan version
    ];

    protected $casts = [
        // No special casts needed
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            // ⭐ AUTO-SET user_id dari user yang login
            if (Auth::check() && !$document->user_id) {
                $document->user_id = Auth::id();
            }

            // Auto-generate code_number jika belum ada
            if (!$document->code_number) {
                $document->code_number = $document->generateCodeNumber();
            }

            // Auto-set version jika belum ada
            if (!$document->version) {
                $document->version = '1.0';
            }

            // Auto-detect document type
            if (!$document->document_type) {
                $document->detectDocumentType();
            }
        });

        static::created(function ($document) {
            // Generate file untuk form/hybrid documents
            if (in_array($document->document_type, ['form', 'hybrid']) && $document->content) {
                $document->generateFileFromContent();
            }
        });
    }

    /**
     * Generate code number: COMPANY-DEPARTMENT-UNIT-CATEGORY-001
     */
    public function generateCodeNumber(): string
    {
        $companyCode = $this->company?->code ?: 'COM';
        $departmentCode = $this->department?->code ?: 'DEPT';
        $unitCode = $this->unit?->code ?: '';
        $categoryCode = $this->category?->code ?: 'CAT';

        // Format dengan atau tanpa unit
        $baseCode = $unitCode
            ? "{$companyCode}-{$departmentCode}-{$unitCode}-{$categoryCode}"
            : "{$companyCode}-{$departmentCode}-{$categoryCode}";

        // Cari nomor terakhir untuk kombinasi ini
        $lastNumber = self::withTrashed()
            ->where('company_id', $this->company_id)
            ->where('department_id', $this->department_id)
            ->where('unit_id', $this->unit_id)
            ->where('category_id', $this->category_id)
            ->whereNotNull('code_number')
            ->count();

        $sequence = $lastNumber + 1;

        return "{$baseCode}-" . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Auto-detect document type
     */
    public function detectDocumentType(): void
    {
        if ($this->file_path && $this->content) {
            $this->document_type = 'hybrid';
        } elseif ($this->file_path) {
            $this->document_type = 'file';
        } elseif ($this->content) {
            $this->document_type = 'form';
        }
    }

    /**
     * Generate HTML file dari content
     */
    public function generateFileFromContent(): bool
    {
        if (!$this->content) return false;

        try {
            $filename = "doc-{$this->id}-" . time() . ".html";
            $filePath = "generated/{$filename}";

            $html = $this->buildHtmlContent();
            Storage::disk('documents')->put($filePath, $html);

            $this->generated_file_path = $filePath;
            $this->saveQuietly();

            return true;
        } catch (\Exception $e) {
            Log::error("Generate file failed: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Build HTML content
     */
    private function buildHtmlContent(): string
    {
        return "
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
                .content { margin-top: 20px; white-space: pre-wrap; }
            </style>
        </head>
        <body>
            <div class='header'>
                <div class='title'>{$this->title}</div>
                <div class='meta'>
                    <strong>Document Number:</strong> {$this->code_number} | 
                    <strong>Type:</strong> {$this->document_type} |
                    <strong>Status:</strong> {$this->status}
                </div>
            </div>
            <div class='content'>{$this->content}</div>
        </body>
        </html>";
    }

    // ========== RELATIONSHIPS ==========
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

    // ========== ACTIVITY LOG ==========
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'code_number', 'status', 'document_type'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Document {$this->code_number} {$eventName}");
    }
}
