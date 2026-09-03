<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates query parameters for GET /api/v1/sops.
 */
class SopIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page'       => ['sometimes', 'integer', 'min:1'],
            'limit'      => ['sometimes', 'integer', 'min:1', 'max:100'],
            'q'          => ['sometimes', 'string', 'max:255'],
            'department' => ['sometimes', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'limit.max' => 'limit may not exceed 100 records per page.',
        ];
    }

    public function getPage(): int        { return (int) ($this->validated('page', 1)); }
    public function getLimit(): int       { return (int) ($this->validated('limit', 10)); }
    public function getSearch(): string   { return trim((string) ($this->validated('q', ''))); }
    public function getDepartment(): string { return trim((string) ($this->validated('department', ''))); }
}
