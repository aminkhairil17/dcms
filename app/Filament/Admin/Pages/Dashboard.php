<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\EmployeeActivityHubWidget;
use App\Filament\Admin\Widgets\MeetingInvitedWidget;
use App\Filament\Admin\Widgets\MeetingStatsWidget;
use App\Filament\Admin\Widgets\MeetingTodayWidget;
use App\Filament\Admin\Widgets\MyCalendarWidget;
use App\Filament\Admin\Widgets\PriorityActionsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dasbor';

    protected static ?string $navigationLabel = 'Dasbor';

    public function getWidgets(): array
    {
        return [
            EmployeeActivityHubWidget::class,
            MeetingStatsWidget::class,
            MeetingTodayWidget::class,
            MeetingInvitedWidget::class,
            MyCalendarWidget::class,
            PriorityActionsWidget::class,
        ];
    }
}
