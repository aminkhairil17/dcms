<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Meeting;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class MeetingStatsWidget extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getHeading(): string
    {
        return 'Statistik Rapat';
    }

    protected function getColumns(): int | array | null
    {
        return [
            'default' => 2,
            'lg'      => 3,
        ];
    }


    protected function getStats(): array
    {
        $user  = auth()->user();
        $today = Carbon::today();

        // ── Today ─────────────────────────────────────────
        $todayQuery = Meeting::query()
            ->access()
            ->where('status', '!=', 'cancelled');

        $todayMeetingsCount = (clone $todayQuery)
            ->whereDate('date_time', $today)
            ->count();

        // ── Invited ───────────────────────────────────────
        $invitedQuery = Meeting::query()
            ->where(function ($q) use ($user) {
                $q->whereHas('participants', fn($q2) => $q2->where('users.id', $user->id))
                    ->orWhere('created_by', $user->id);
            })
            ->whereNotIn('status', ['cancelled', 'completed']);

        $invitedMeetingsCount = (clone $invitedQuery)
            ->where('date_time', '>=', now())
            ->count();

        // ── Total Upcoming ────────────────────────────────
        $totalQuery = Meeting::query()
            ->access()
            ->whereNotIn('status', ['cancelled', 'completed']);

        $totalMeetingsCount = (clone $totalQuery)
            ->where('date_time', '>=', now())
            ->count();

        return [
            Stat::make('Rapat Hari Ini', $todayMeetingsCount)
                ->description('Rapat terjadwal hari ini')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Undangan Rapat', $invitedMeetingsCount)
                ->description('Rapat di mana Anda peserta')
                ->descriptionIcon('heroicon-m-envelope')
                ->color('info'),

            Stat::make('Rapat Mendatang', $totalMeetingsCount)
                ->description('Semua agenda yang akan datang')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('success'),
        ];
    }
}
