<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Meeting;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class MeetingStatsWidget extends Widget
{
    use HasWidgetShield;

    protected string $view = 'filament.widgets.meeting-stats-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getPollingInterval(): ?string
    {
        return '30s';
    }

    protected function getViewData(): array
    {
        $user  = auth()->user();
        $today = Carbon::today();

        // Today's meetings
        $todayCount = Meeting::query()
            ->access()
            ->where('status', '!=', 'cancelled')
            ->whereDate('date_time', $today)
            ->count();

        // Invited / participating meetings
        $invitedCount = Meeting::query()
            ->where(function ($q) use ($user) {
                $q->whereHas('participants', fn($q2) => $q2->where('users.id', $user->id))
                  ->orWhere('created_by', $user->id);
            })
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->where('date_time', '>=', now())
            ->count();

        // Total upcoming meetings
        $upcomingCount = Meeting::query()
            ->access()
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->where('date_time', '>=', now())
            ->count();

        return [
            'todayCount'   => $todayCount,
            'invitedCount' => $invitedCount,
            'upcomingCount' => $upcomingCount,
        ];
    }
}
