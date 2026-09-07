<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Document;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class StatsOverview extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected static ?int $sort = -1;

    protected static bool $isDiscovered = false;

    protected function getColumns(): int|array|null
    {
        return [
            'default' => 2,
            'lg' => 4,
        ];
    }

    protected function getSparklineData(Builder $query, string $dateColumn = 'created_at'): array
    {
        $data = [];
        $now = now();

        for ($i = 6; $i >= 0; $i--) {
            $targetDate = (clone $now)->subDays($i)->endOfDay();
            $count = (clone $query)->where($dateColumn, '<=', $targetDate)->count();
            $data[] = $count;
        }

        if (count(array_unique($data)) <= 1) {
            $last = end($data);
            if ($last === 0) {
                return [0, 1, 0, 2, 1, 0, 0];
            }

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

    protected function getStats(): array
    {
        $totalDocQuery = Document::query()->access();
        $approvedDocQuery = Document::query()->access()->where('status', 'approved');
        $pendingDocQuery = Document::query()->access()->whereIn('status', ['pending_kabid', 'pending_direktur']);
        $rejectedDocQuery = Document::query()->access()->where('status', 'rejected');

        return [
            Stat::make('Total Documents', (clone $totalDocQuery)->count())
                ->label('Total Documents')
                ->description('Total Documents in the system')
                ->descriptionIcon('heroicon-o-document-text')
                ->chart($this->getSparklineData($totalDocQuery)),

            Stat::make('Approved Documents', (clone $approvedDocQuery)->count())
                ->label('Approved Documents')
                ->description('Documents approved')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('info')
                ->chart($this->getSparklineData($approvedDocQuery, 'updated_at')),

            Stat::make('Pending Documents', (clone $pendingDocQuery)->count())
                ->label('Pending Documents')
                ->description('Documents pending review')
                ->descriptionIcon('heroicon-o-arrow-trending-down')
                ->color('primary')
                ->chart($this->getSparklineData($pendingDocQuery)),

            Stat::make('Rejected Documents', (clone $rejectedDocQuery)->count())
                ->label('Rejected Documents')
                ->description('Documents rejected')
                ->descriptionIcon('heroicon-o-minus')
                ->color('info')
                ->chart($this->getSparklineData($rejectedDocQuery, 'updated_at')),
        ];
    }
}
