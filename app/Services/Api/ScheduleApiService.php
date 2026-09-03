<?php

namespace App\Services\Api;

use App\Models\Meeting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * ScheduleApiService
 *
 * Encapsulates all Meeting query logic for the read-only Schedule API.
 *
 * Status mapping (API query param → DB status):
 *  - "upcoming"  → status IN ('draft', 'ongoing') AND date_time >= NOW()
 *  - "completed" → status = 'completed'
 *  - "canceled"  → status = 'cancelled'  (note: DB uses British spelling)
 */
class ScheduleApiService
{
    /**
     * Retrieve a paginated list of meeting schedules with optional filters.
     *
     * @param array{
     *   startDate: string|null,
     *   endDate: string|null,
     *   status: string,
     *   participant_id: int|null,
     * } $filters
     */
    public function getPaginated(array $filters): LengthAwarePaginator
    {
        $query = Meeting::withoutGlobalScope('latest')
            ->with([
                'creator:id,name,email',
                'notulis:id,name',
                'company:id,name',
                'department:id,name',
                'unit:id,name',
                'participants:id,name,email',
            ])
            ->orderBy('date_time', 'desc');

        // API ini HANYA menampilkan rapat yang "jadi" (tidak dibatalkan).
        // Rapat dengan status 'cancelled' tidak pernah ikut tampil.
        $query->where('status', '!=', 'cancelled');

        // --- Date range filter ---
        if (filled($filters['startDate'])) {
            $query->whereDate('date_time', '>=', $filters['startDate']);
        }

        if (filled($filters['endDate'])) {
            $query->whereDate('date_time', '<=', $filters['endDate']);
        }

        // --- Status filter with API → DB mapping ---
        if (filled($filters['status'])) {
            $query = $this->applyStatusFilter($query, $filters['status']);
        }

        // --- Participant filter ---
        if (! empty($filters['participant_id'])) {
            $pid = (int) $filters['participant_id'];
            $query->whereHas('participants', fn ($q) => $q->where('users.id', $pid));
        }

        return $query->paginate(perPage: 10);
    }

    /**
     * Retrieve a single meeting with full detail.
     * Includes: participants (with attendance), creator, notulis, and attachments.
     */
    public function getById(int $id): ?Meeting
    {
        return Meeting::withoutGlobalScope('latest')
            ->with([
                'creator:id,name,email',
                'notulis:id,name',
                'company:id,name',
                'department:id,name',
                'unit:id,name',
                'participants:id,name,email',
            ])
            ->find($id);
    }

    /**
     * Translate API status parameter into DB query condition.
     */
    private function applyStatusFilter(\Illuminate\Database\Eloquent\Builder $query, string $apiStatus): \Illuminate\Database\Eloquent\Builder
    {
        return match ($apiStatus) {
            'upcoming' => $query
                ->whereIn('status', ['draft', 'ongoing'])
                ->where('date_time', '>=', now()),

            'completed' => $query->where('status', 'completed'),

            // "canceled" in the API maps to "cancelled" in the DB
            'canceled'  => $query->where('status', 'cancelled'),

            default => $query,
        };
    }
}
