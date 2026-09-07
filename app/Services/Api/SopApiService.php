<?php

namespace App\Services\Api;

use App\Models\Document;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * SopApiService
 *
 * SOPs are Documents whose associated DocumentCategory has a name or prefix
 * containing "SOP" (case-insensitive). This service applies that filter
 * automatically so controllers never need to replicate it.
 */
class SopApiService
{
    /**
     * The category identifier used to scope SOP documents.
     * Matches categories where `name` or `prefix` contains this value.
     */
    private const SOP_IDENTIFIER = 'SOP';

    /**
     * Retrieve a paginated list of SOPs with optional filters.
     *
     * @param array{
     *   page: int,
     *   limit: int,
     *   q: string,
     *   department: string,
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
                'unit:id,name,code,prefix',
                'category:id,name,prefix',
            ])
            ->withoutTrashed()
            // Core SOP filter: only documents from SOP categories
            ->whereHas('category', fn ($q) => $q->where('name', 'LIKE', '%'.self::SOP_IDENTIFIER.'%')
                ->orWhere('prefix', 'LIKE', '%'.self::SOP_IDENTIFIER.'%')
            );

        // --- Full-text search ---
        if (filled($filters['q'])) {
            $query->fullTextSearch($filters['q']);
        }

        // --- Department filter (name or code) ---
        if (filled($filters['department'])) {
            $dept = $filters['department'];
            $query->whereHas('department', function ($q) use ($dept) {
                $q->where('name', 'LIKE', "%{$dept}%")
                    ->orWhere('code', 'LIKE', "%{$dept}%");
            });
        }

        // Default: newest first
        $query->orderBy('created_at', 'desc');

        return $query->paginate(
            perPage: $filters['limit'],
            page: $filters['page'],
        );
    }

    /**
     * Retrieve a single SOP document with full detail, including:
     * - All organisational relations
     * - Activity log (for revision history in SopResource)
     *
     * Returns null if the document is not found or is not a SOP.
     */
    public function getById(int $id): ?Document
    {
        $doc = Document::query()
            ->with([
                'user:id,name,email',
                'updatedByUser:id,name',
                'kabidReviewer:id,name',
                'direkturReviewer:id,name',
                'company:id,name,code',
                'department:id,name,code',
                'unit:id,name,code,prefix',
                'category:id,name,prefix',
                // Load activity log for revision history
                'activityLogs' => fn ($q) => $q->latest()->limit(50),
                'activityLogs.causer:id,name',
            ])
            ->withoutTrashed()
            ->find($id);

        // Confirm the document belongs to a SOP category
        if (! $doc) {
            return null;
        }

        $isSop = $doc->category
            && (
                str_contains(strtoupper((string) $doc->category->name), self::SOP_IDENTIFIER) ||
                str_contains(strtoupper((string) $doc->category->prefix), self::SOP_IDENTIFIER)
            );

        return $isSop ? $doc : null;
    }
}
