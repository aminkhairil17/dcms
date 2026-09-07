<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DocumentIndexRequest;
use App\Http\Resources\Api\DocumentResource;
use App\Services\Api\DocumentApiService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * DocumentController
 *
 * Handles read-only Document API endpoints.
 *
 * Routes:
 *   GET /api/v1/documents        → index()
 *   GET /api/v1/documents/{id}   → show()
 */
class DocumentController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly DocumentApiService $service,
    ) {}

    /**
     * GET /api/v1/documents
     *
     * Returns a paginated list of documents with optional filters:
     *   - q         : full-text search (title, code_number, description)
     *   - category  : category name or prefix (partial match)
     *   - status    : document status (draft|pending_kabid|pending_direktur|approved|rejected|archived)
     *   - sortBy    : field to sort by (default: created_at)
     *   - order     : sort direction — asc|desc (default: desc)
     *   - page      : page number (default: 1)
     *   - limit     : records per page, max 100 (default: 10)
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Data retrieved successfully",
     *   "data": [...],
     *   "meta": { "page": 1, "limit": 10, "totalItems": 42, "totalPages": 5 }
     * }
     */
    public function index(DocumentIndexRequest $request): JsonResponse
    {
        $paginator = $this->service->getPaginated([
            'page' => $request->getPage(),
            'limit' => $request->getLimit(),
            'q' => $request->getSearch(),
            'category' => $request->getCategory(),
            'status' => $request->getStatus(),
            'sortBy' => $request->getSortBy(),
            'order' => $request->getOrder(),
        ]);

        $collection = DocumentResource::collection($paginator);

        return $this->paginatedResponse($collection);
    }

    /**
     * GET /api/v1/documents/{id}
     *
     * Returns full metadata for a single document identified by its ID.
     * Includes: author info, review workflow trail, organisational hierarchy,
     * file URL, expiry info, and all relation data.
     *
     * @response 200 { "success": true, "message": "...", "data": {...} }
     * @response 404 { "success": false, "message": "Document not found" }
     */
    public function show(int $id): JsonResponse
    {
        $document = $this->service->getById($id);

        if (! $document) {
            return $this->notFoundResponse('Document not found.');
        }

        return $this->successResponse(
            data: new DocumentResource($document),
            message: 'Document retrieved successfully.',
        );
    }
}
