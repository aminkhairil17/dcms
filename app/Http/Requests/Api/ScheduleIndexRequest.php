<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates query parameters for GET /api/v1/schedules.
 *
 * Status mapping:
 *  - "upcoming"   → meetings with status IN (draft, ongoing) AND date_time >= now
 *  - "completed"  → status = completed
 *  - "canceled"   → status = cancelled  (DB uses British spelling)
 */
class ScheduleIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'startDate'      => ['sometimes', 'date_format:Y-m-d'],
            'endDate'        => ['sometimes', 'date_format:Y-m-d', 'after_or_equal:startDate'],
            'status'         => ['sometimes', 'string', 'in:upcoming,completed,canceled'],
            'participant_id' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'startDate.date_format' => 'startDate must be in Y-m-d format (e.g. 2026-08-01).',
            'endDate.date_format'   => 'endDate must be in Y-m-d format (e.g. 2026-08-31).',
            'endDate.after_or_equal'=> 'endDate must be on or after startDate.',
            'status.in'             => 'status must be one of: upcoming, completed, canceled.',
        ];
    }

    public function getStartDate(): ?string  { return $this->validated('startDate'); }
    public function getEndDate(): ?string    { return $this->validated('endDate'); }
    public function getStatus(): string      { return trim((string) ($this->validated('status', ''))); }
    public function getParticipantId(): ?int
    {
        $val = $this->validated('participant_id');
        return $val !== null ? (int) $val : null;
    }
}
