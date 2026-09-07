<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates query parameters for GET /api/v1/documents.
 */
class DocumentIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'q' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', 'string', 'max:100'],
            'status' => ['sometimes', 'string', 'in:draft,pending_kabid,pending_direktur,approved,rejected,archived'],
            'sortBy' => ['sometimes', 'string', 'in:title,code_number,version,created_at,updated_at,expires_at'],
            'order' => ['sometimes', 'string', 'in:asc,desc'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'status must be one of: draft, pending_kabid, pending_direktur, approved, rejected, archived.',
            'sortBy.in' => 'sortBy must be one of: title, code_number, version, created_at, updated_at, expires_at.',
            'order.in' => 'order must be asc or desc.',
            'limit.max' => 'limit may not exceed 100 records per page.',
        ];
    }

    /**
     * Convenience helpers — returns validated values with sensible defaults.
     */
    public function getPage(): int
    {
        return (int) ($this->validated('page', 1));
    }

    public function getLimit(): int
    {
        return (int) ($this->validated('limit', 10));
    }

    public function getSearch(): string
    {
        return trim((string) ($this->validated('q', '')));
    }

    public function getCategory(): string
    {
        return trim((string) ($this->validated('category', '')));
    }

    public function getStatus(): string
    {
        return trim((string) ($this->validated('status', '')));
    }

    public function getSortBy(): string
    {
        return $this->validated('sortBy', 'created_at');
    }

    public function getOrder(): string
    {
        return $this->validated('order', 'desc');
    }
}
