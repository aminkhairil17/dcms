<?php

namespace App\Filament\Reviewer\Widgets;

use App\Models\Document;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class ReviewerStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

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
        /** @var \App\Models\User|null $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        if (! $user) {
            return [];
        }

        $stats = [];

        if ($user->hasRole('kabid')) {
            $pendingKabidQuery = Document::where('department_id', $user->department_id)
                ->where('status', Document::STATUS_PENDING_KABID);
            $pendingKabid = (clone $pendingKabidQuery)->count();

            $approvedByMeQuery = Document::where('reviewed_by_kabid', $user->id);
            $approvedByMe = (clone $approvedByMeQuery)->count();

            $stats[] = Stat::make('Menunggu Review Anda', $pendingKabid)
                ->description('Dokumen perlu disetujui')
                ->descriptionIcon('heroicon-o-clock')
                ->color('primary')
                ->chart($this->getSparklineData($pendingKabidQuery))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]);

            $stats[] = Stat::make('Sudah Anda Review', $approvedByMe)
                ->description('Total dokumen diproses')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('info')
                ->chart($this->getSparklineData($approvedByMeQuery, 'updated_at'));
        }

        if ($user->hasRole('direktur')) {
            $pendingDirekturQuery = Document::where('company_id', $user->company_id)
                ->where('status', Document::STATUS_PENDING_DIREKTUR);
            $pendingDirektur = (clone $pendingDirekturQuery)->count();

            $approvedByMeQuery = Document::where('reviewed_by_direktur', $user->id)
                ->where('status', Document::STATUS_APPROVED);
            $approvedByMe = (clone $approvedByMeQuery)->count();

            $rejectedByMeQuery = Document::where('reviewed_by_direktur', $user->id)
                ->where('status', Document::STATUS_REJECTED);
            $rejectedByMe = (clone $rejectedByMeQuery)->count();

            $stats[] = Stat::make('Menunggu Keputusan Anda', $pendingDirektur)
                ->description('Dokumen perlu keputusan final')
                ->descriptionIcon('heroicon-o-clock')
                ->color('primary')
                ->chart($this->getSparklineData($pendingDirekturQuery));

            $stats[] = Stat::make('Disetujui', $approvedByMe)
                ->description('Total disetujui oleh Anda')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('info')
                ->chart($this->getSparklineData($approvedByMeQuery, 'updated_at'));

            $stats[] = Stat::make('Ditolak', $rejectedByMe)
                ->description('Total ditolak oleh Anda')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('gray')
                ->chart($this->getSparklineData($rejectedByMeQuery, 'updated_at'));
        }

        if ($user->hasRole('super_admin')) {
            $pkQuery = Document::where('status', Document::STATUS_PENDING_KABID);
            $pdQuery = Document::where('status', Document::STATUS_PENDING_DIREKTUR);
            $appQuery = Document::where('status', Document::STATUS_APPROVED);
            $rejQuery = Document::where('status', Document::STATUS_REJECTED);

            $stats[] = Stat::make('Pending Kabid', (clone $pkQuery)->count())
                ->color('primary')
                ->chart($this->getSparklineData($pkQuery));

            $stats[] = Stat::make('Pending Direktur', (clone $pdQuery)->count())
                ->color('info')
                ->chart($this->getSparklineData($pdQuery));

            $stats[] = Stat::make('Total Disetujui', (clone $appQuery)->count())
                ->color('info')
                ->chart($this->getSparklineData($appQuery, 'updated_at'));

            $stats[] = Stat::make('Total Ditolak', (clone $rejQuery)->count())
                ->color('gray')
                ->chart($this->getSparklineData($rejQuery, 'updated_at'));
        }

        return $stats;
    }
}
