<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ScheduleIndexRequest;
use App\Http\Resources\Api\ScheduleResource;
use App\Services\Api\ScheduleApiService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * ScheduleController
 *
 * Handles read-only Meeting Schedule API endpoints.
 *
 * Routes:
 *   GET /api/v1/schedules        → index()
 *   GET /api/v1/schedules/{id}   → show()
 */
class ScheduleController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ScheduleApiService $service,
    ) {}

    /**
     * GET /api/v1/schedules
     *
     * Returns a paginated list of meeting schedules with optional filters:
     *   - startDate      : filter meetings on or after this date (Y-m-d)
     *   - endDate        : filter meetings on or before this date (Y-m-d)
     *   - status         : upcoming | completed | canceled
     *   - participant_id : filter meetings where this user ID is a participant
     *
     * Status mapping:
     *   upcoming  → DB status IN (draft, ongoing) AND date_time >= now
     *   completed → DB status = completed
     *   canceled  → DB status = cancelled
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Data retrieved successfully",
     *   "data": [...],
     *   "meta": { "page": 1, "limit": 10, "totalItems": 8, "totalPages": 1 }
     * }
     */
    public function index(ScheduleIndexRequest $request): JsonResponse
    {
        $paginator = $this->service->getPaginated([
            'startDate'      => $request->getStartDate(),
            'endDate'        => $request->getEndDate(),
            'status'         => $request->getStatus(),
            'participant_id' => $request->getParticipantId(),
        ]);

        $collection = ScheduleResource::collection($paginator);

        return $this->paginatedResponse($collection);
    }

    /**
     * GET /api/v1/schedules/{id}
     *
     * Returns full detail for a single meeting schedule including:
     *   - Agenda and content (meeting notes / notulen)
     *   - Location / meeting link
     *   - Attendee list with attendance status (confirmed|pending|declined)
     *   - Creator and notulis info
     *   - Attachments with resolved file URLs
     *   - Organisational hierarchy (company, department, unit)
     *
     * @response 200 { "success": true, "message": "...", "data": {...} }
     * @response 404 { "success": false, "message": "Meeting schedule not found" }
     */
    public function show(int $id): JsonResponse
    {
        $meeting = $this->service->getById($id);

        if (! $meeting) {
            return $this->notFoundResponse('Meeting schedule not found.');
        }

        return $this->successResponse(
            data: new ScheduleResource($meeting),
            message: 'Meeting schedule retrieved successfully.',
        );
    }
}
