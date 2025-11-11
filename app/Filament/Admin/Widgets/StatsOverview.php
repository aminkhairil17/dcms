<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Document;
use App\Filament\Admin\Widgets\DocumentStatsOverview;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Documents', Document::count())
                ->label('Total Documents')
                ->description('Total Documents in the system')
                ->descriptionIcon('heroicon-o-document-text'),
            Stat::make('Approved Documents', Document::where('status', 'approved')->count())
                ->label('Approved Documents')
                ->description('8% increase from last month')
                ->descriptionIcon('heroicon-o-arrow-trending-up'),
            Stat::make('Pending Documents', Document::where('status', 'pending')->count())
                ->label('Pending Documents')
                ->description('2% decrease from last month')
                ->descriptionIcon('heroicon-o-arrow-trending-down'),
            Stat::make('Rejected Documents', Document::where('status', 'rejected')->count())
                ->label('Rejected Documents')
                ->description('No change from last month')
                ->descriptionIcon('heroicon-o-minus'),
        ];
    }
}
