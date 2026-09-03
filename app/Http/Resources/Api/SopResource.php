<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Transforms a Document (used as SOP) into a richer JSON structure,
 * including parsed step-by-step procedures and revision history.
 *
 * SOPs are Documents whose category has the prefix "SOP" (case-insensitive).
 */
class SopResource extends DocumentResource
{
    public function toArray(Request $request): array
    {
        $base = parent::toArray($request);

        return array_merge($base, [
            // Parsed procedure steps extracted from the `content` field.
            // Supports numbered/bulleted lists or paragraph-separated steps.
            'procedures' => $this->parseProcedures(),

            // Revision history derived from the activity log (version changes only).
            'revision_history' => $this->buildRevisionHistory(),

            // Related documents from the same department and category.
            'related_documents' => $this->whenLoaded('category', function () {
                return \App\Models\Document::query()
                    ->where('category_id', $this->category_id)
                    ->where('id', '!=', $this->id)
                    ->where('status', \App\Models\Document::STATUS_APPROVED)
                    ->limit(5)
                    ->get(['id', 'title', 'code_number', 'version', 'status'])
                    ->map(fn ($doc) => [
                        'id'          => $doc->id,
                        'title'       => $doc->title,
                        'code_number' => $doc->code_number,
                        'version'     => $doc->version,
                    ])
                    ->values()
                    ->all();
            }),
        ]);
    }

    /**
     * Parse the document's `content` field into an ordered steps array.
     *
     * Handles three common formats:
     *  1. Numbered list: "1. Step one\n2. Step two"
     *  2. Bulleted list: "- Step one\n* Step two"
     *  3. Plain paragraphs separated by double newlines
     */
    private function parseProcedures(): array
    {
        $content = $this->content;

        if (blank($content)) {
            return [];
        }

        $lines = preg_split('/\r?\n/', trim($content));
        $steps = [];
        $stepNumber = 1;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            // Strip leading list markers: "1.", "-", "*", "•"
            $cleaned = preg_replace('/^(\d+\.|\-|\*|•)\s*/', '', $line);

            if ($cleaned !== '') {
                $steps[] = [
                    'step'        => $stepNumber++,
                    'description' => $cleaned,
                ];
            }
        }

        return $steps;
    }

    /**
     * Build a revision history from the Spatie Activity Log.
     * Filters log entries that touched the `version` or `status` fields.
     */
    private function buildRevisionHistory(): array
    {
        if (! $this->relationLoaded('activityLogs')) {
            return [];
        }

        return $this->activityLogs
            ->filter(function ($log) {
                $properties = $log->properties ?? collect();
                $changed = $properties->get('attributes', []);

                return isset($changed['version']) || isset($changed['status']);
            })
            ->map(fn ($log) => [
                'event'      => $log->event ?? $log->description,
                'version'    => $log->properties['attributes']['version'] ?? null,
                'status'     => $log->properties['attributes']['status'] ?? null,
                'changed_by' => $log->causer?->name,
                'changed_at' => $log->created_at?->toISOString(),
                'description'=> Str::limit($log->description, 120),
            ])
            ->sortByDesc('changed_at')
            ->values()
            ->all();
    }
}
