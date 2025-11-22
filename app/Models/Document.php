<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $year = date('Y');

        // Cari document numbering berdasarkan kombinasi
        $documentNumbering = DocumentNumbering::where([
            'category_id' => $this->category_id,
            'company_id' => $this->company_id,
            'department_id' => $this->department_id,
            'year' => $year,
        ])->first();

        // Jika tidak ditemukan, buat baru
        if (!$documentNumbering) {
            $documentNumbering = DocumentNumbering::create([
                'category_id' => $this->category_id,
                'company_id' => $this->company_id,
                'department_id' => $this->department_id,
                'year' => $year,
                'last_number' => 0,
            ]);
        }

        // Increment last_number dengan transaction untuk menghindari race condition
        DB::transaction(function () use ($documentNumbering) {
            $documentNumbering->lockForUpdate();
            $documentNumbering->increment('last_number');
        });

        // Refresh untuk mendapatkan last_number terbaru
        $documentNumbering->refresh();

        // Generate kode perusahaan, departemen, dan kategori
        $companyCode = $this->company?->code ?: 'COM';
        $departmentCode = $this->department?->code ?: 'DEPT';
        $categoryCode = $this->category?->prefix ?: 'CAT';
        $unitCode = $this->unit?->code ?: '';

        // Format nomor dokumen
        $sequence = str_pad($documentNumbering->last_number, 3, '0', STR_PAD_LEFT);

        // Pilih format berdasarkan ada/tidaknya unit
        if (!empty($unitCode)) {
            return "{$companyCode}-{$departmentCode}-{$unitCode}-{$categoryCode}-{$sequence}";
        } else {
            return "{$companyCode}-{$departmentCode}-{$categoryCode}-{$sequence}";
        }
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
    public function scopeAccess(Builder $query)
    {
        $user = auth()->user();

        if ($user->hasRole('super_admin')) {
            // Super admin has access to all documents
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            if ($user->hasRole('direktur')) {
                // Direktur akses semua dokumen dalam perusahaan
                $q->where('company_id', $user->company_id);
            } elseif ($user->hasRole('manager')) {
                // Manager akses semua dokumen dalam departemen
                $q->where('department_id', $user->department_id);
            } else {
                // Staff akses dokumen unit-nya sendiri
                $q->where('unit_id', $user->unit_id);
            }
        });
    }
}
