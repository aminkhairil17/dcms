<?php

namespace App\Services\Api;

use App\Models\Document;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * DocumentApiService
 *
 * Encapsulates all query-building logic for the read-only Document API endpoints.
 * Keeps controllers thin and makes query logic independently testable.
 */
class DocumentApiService
{
    /**
     * Retrieve a paginated, filtered, sorted list of documents.
     *
     * @param array{
     *   page: int,
     *   limit: int,
     *   q: string,
     *   category: string,
     *   status: string,
     *   sortBy: string,
     *   order: string,
     * } $filters
     */
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        $query = Document::query()
            ->with([
                'user:id,name,email',
                'updatedByUser:id,name',
                'kabidReviewer:id,name',
                'direkturReviewer:id,name',
                'company:id,name,code',
                'department:id,name,code',
                // 'unit:id,name,code,prefix',
                'category:id,name,prefix',
            ])
            ->withoutTrashed();

        // --- Full-text / keyword search ---
        if (filled($filters['q'])) {
            $query->fullTextSearch($filters['q']);
        }

        // --- Category filter (name or prefix, case-insensitive) ---
        if (filled($filters['category'])) {
            $cat = $filters['category'];
            $query->whereHas('category', function ($q) use ($cat) {
                $q->where('name', 'LIKE', "%{$cat}%")
                    ->orWhere('prefix', 'LIKE', "%{$cat}%");
            });
        }

        // --- Status filter ---
        // API ini HANYA menampilkan dokumen yang sudah di-acc/disetujui (approved).
        // Filter status dari request diabaikan agar dokumen yang belum final
        // (draft, pending_kabid, pending_direktur, rejected, archived) tidak ikut tampil.
        $query->where('status', Document::STATUS_APPROVED);

        // --- Sorting (whitelist enforced by FormRequest) ---
        $sortBy = $filters['sortBy'] ?? 'created_at';
        $order  = $filters['order']  ?? 'desc';
        $query->orderBy($sortBy, $order);

        return $query->paginate(
            perPage: $filters['limit'],
            page: $filters['page'],
        );
    }

    /**
     * Retrieve a single document with full metadata relations.
     * Returns null if the document does not exist or is soft-deleted.
     */
    public function getById(int $id): ?Document
    {
        return Document::query()
            ->with([
                'user:id,name,email',
                'updatedByUser:id,name',
                'kabidReviewer:id,name',
                'direkturReviewer:id,name',
                'company:id,name,code',
                'department:id,name,code',
                'unit:id,name,code,prefix',
                'category:id,name,prefix',
            ])
            ->withoutTrashed()
            ->find($id);
    }
}
