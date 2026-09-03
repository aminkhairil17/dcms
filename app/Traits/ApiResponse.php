<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Trait ApiResponse
 *
 * Provides a consistent JSON envelope for all API responses.
 *
 * Success format:
 * {
 *   "success": true,
 *   "message": "...",
 *   "data": {} | [],
 *   "meta": { "page", "limit", "totalItems", "totalPages" }  // paginated only
 * }
 *
 * Error format:
 * {
 *   "success": false,
 *   "message": "...",
 *   "errors": {}  // optional
 * }
 */
trait ApiResponse
{
    /**
     * Return a standardised success JSON response.
     */
    protected function successResponse(
        mixed $data,
        string $message = 'Data retrieved successfully',
        int $status = 200,
        ?array $meta = null,
    ): JsonResponse {
        $payload = [
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ];

        if ($meta !== null) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    /**
     * Return a standardised paginated JSON response.
     * Wraps any LengthAwarePaginator or ResourceCollection result.
     */
    protected function paginatedResponse(
        ResourceCollection $collection,
        string $message = 'Data retrieved successfully',
    ): JsonResponse {
        $paginator = $collection->resource;

        return $this->successResponse(
            data: $collection,
            message: $message,
            meta: [
                'page'       => $paginator->currentPage(),
                'limit'      => $paginator->perPage(),
                'totalItems' => $paginator->total(),
                'totalPages' => $paginator->lastPage(),
            ],
        );
    }

    /**
     * Return a standardised error JSON response.
     */
    protected function errorResponse(
        string $message,
        int $status = 400,
        ?array $errors = null,
    ): JsonResponse {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }

    /**
     * Return a 404 Not Found response.
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }
}
