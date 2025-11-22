<?php

namespace App\Filament\Admin\Resources\Documents\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;

class MyWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Documents', Document::query()->access()->count())
                ->label('Total Documents')
                ->description('Total Documents in the system')
                ->descriptionIcon('heroicon-o-document-text'),
            Stat::make('Approved Documents', Document::query()->access()->where('status', 'approved')->count())
                ->label('Approved Documents')
                ->description('8% increase from last month')
                ->descriptionIcon('heroicon-o-arrow-trending-up'),
            Stat::make('Pending Documents', Document::query()->access()->where('status', 'pending')->count())
                ->label('Pending Documents')
                ->description('2% decrease from last month')
                ->descriptionIcon('heroicon-o-arrow-trending-down'),
            Stat::make('Rejected Documents', Document::query()->access()->where('status', 'rejected')->count())
                ->label('Rejected Documents')
                ->description('No change from last month')
                ->descriptionIcon('heroicon-o-minus'),
        ];
    }
}
