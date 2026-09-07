<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SopIndexRequest;
use App\Http\Resources\Api\SopResource;
use App\Services\Api\SopApiService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * SopController
 *
 * Handles read-only SOP (Standard Operating Procedure) API endpoints.
 * SOPs are Documents whose DocumentCategory has a name/prefix containing "SOP".
 *
 * Routes:
 *   GET /api/v1/sops        → index()
 *   GET /api/v1/sops/{id}   → show()
 */
class SopController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly SopApiService $service,
    ) {}

    /**
     * GET /api/v1/sops
     *
     * Returns a paginated list of SOP documents with optional filters:
     *   - q          : full-text search (title, code_number, description)
     *   - department : department name or code (partial match)
     *   - page       : page number (default: 1)
     *   - limit      : records per page, max 100 (default: 10)
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Data retrieved successfully",
     *   "data": [...],
     *   "meta": { "page": 1, "limit": 10, "totalItems": 12, "totalPages": 2 }
     * }
     */
    public function index(SopIndexRequest $request): JsonResponse
    {
        $paginator = $this->service->getPaginated([
            'page' => $request->getPage(),
            'limit' => $request->getLimit(),
            'q' => $request->getSearch(),
            'department' => $request->getDepartment(),
        ]);

        $collection = SopResource::collection($paginator);

        return $this->paginatedResponse($collection);
    }

    /**
     * GET /api/v1/sops/{id}
     *
     * Returns full SOP detail including:
     *   - Parsed step-by-step procedures (from content field)
     *   - Revision history (from activity log — version/status changes)
     *   - Related documents from the same category
     *   - Full author, review, and organisational metadata
     *
     * Returns 404 if the document doesn't exist or is not categorised as a SOP.
     *
     * @response 200 { "success": true, "message": "...", "data": {...} }
     * @response 404 { "success": false, "message": "SOP not found" }
     */
    public function show(int $id): JsonResponse
    {
        $sop = $this->service->getById($id);

        if (! $sop) {
            return $this->notFoundResponse('SOP not found. The document may not exist or is not categorised as an SOP.');
        }

        return $this->successResponse(
            data: new SopResource($sop),
            message: 'SOP retrieved successfully.',
        );
    }
}
