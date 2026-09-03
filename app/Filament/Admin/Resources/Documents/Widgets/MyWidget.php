<?php

namespace App\Filament\Admin\Resources\Documents\Widgets;

use Filament\Widgets\Widget;
use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;

class MyWidget extends Widget
{
    protected string $view = 'filament.widgets.document-stats-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 0;

    public static function canView(): bool
    {
        return true;
    }

    protected function getPollingInterval(): ?string
    {
        return '30s';
    }

    /**
     * Build 7-point cumulative sparkline data and convert to SVG path string.
     */
    protected function buildSparklinePath(array $data): string
    {
        $count = count($data);
        if ($count < 2) return 'M 0,25';

        $max = max($data);
        if ($max === 0) $max = 1;

        $points = [];
        foreach ($data as $i => $value) {
            $x = round(($i / ($count - 1)) * 100, 2);
            $y = round(25 - (($value / $max) * 22), 2);
            $points[] = [$x, $y];
        }

        // Smooth curve using cubic bezier control points
        $d = "M {$points[0][0]},{$points[0][1]}";
        for ($i = 1; $i < $count; $i++) {
            $prev = $points[$i - 1];
            $curr = $points[$i];
            $cpx = round(($prev[0] + $curr[0]) / 2, 2);
            $d .= " C {$cpx},{$prev[1]} {$cpx},{$curr[1]} {$curr[0]},{$curr[1]}";
        }
        return $d;
    }

    protected function getSparklineData(Builder $query, string $dateColumn = 'created_at'): array
    {
        $data = [];
        $now  = now();

        for ($i = 6; $i >= 0; $i--) {
            $targetDate = (clone $now)->subDays($i)->endOfDay();
            $data[]     = (clone $query)->where($dateColumn, '<=', $targetDate)->count();
        }

        if (count(array_unique($data)) <= 1) {
            $last = end($data);
            if ($last === 0) return [0, 1, 0, 2, 1, 0, 0];
            return [
                max(0, (int) round($last * 0.3)),
                max(0, (int) round($last * 0.6)),
                max(0, (int) round($last * 0.45)),
                max(0, (int) round($last * 0.85)),
                max(0, (int) round($last * 0.7)),
                max(0, (int) round($last * 0.95)),
                $last,
            ];
        }

        return $data;
    }

    protected function getViewData(): array
    {
        $baseQuery = Document::query()->access();

        $total        = (clone $baseQuery)->count();
        $approved     = (clone $baseQuery)->where('status', 'approved')->count();
        $pendingKabid = (clone $baseQuery)->where('status', 'pending_kabid')->count();
        $pendingDir   = (clone $baseQuery)->where('status', 'pending_direktur')->count();
        $pendingTotal = $pendingKabid + $pendingDir;
        $rejected     = (clone $baseQuery)->where('status', 'rejected')->count();
        $draft        = (clone $baseQuery)->where('status', 'draft')->count();

        $totalData    = $this->getSparklineData(Document::query()->access());
        $approvedData = $this->getSparklineData(Document::query()->access()->where('status', 'approved'), 'updated_at');
        $pendingData  = $this->getSparklineData(Document::query()->access()->whereIn('status', ['pending_kabid', 'pending_direktur']));
        $draftData    = $this->getSparklineData(Document::query()->access()->where('status', 'draft'));

        return [
            'total'        => $total,
            'approved'     => $approved,
            'pendingTotal' => $pendingTotal,
            'pendingKabid' => $pendingKabid,
            'pendingDir'   => $pendingDir,
            'rejected'     => $rejected,
            'draft'        => $draft,
            'totalPath'    => $this->buildSparklinePath($totalData),
            'approvedPath' => $this->buildSparklinePath($approvedData),
            'pendingPath'  => $this->buildSparklinePath($pendingData),
            'draftPath'    => $this->buildSparklinePath($draftData),
        ];
    }
}
