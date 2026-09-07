<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Meeting;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;
use Illuminate\Support\Carbon;

class MeetingTodayWidget extends BaseTableWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Jadwal Rapat Hari Ini';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Meeting::query()
                    ->access()
                    ->whereDate('date_time', Carbon::today())
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('date_time', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Rapat'),
                Tables\Columns\TextColumn::make('date_time')
                    ->label('Waktu')
                    ->dateTime('H:i'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'ongoing' => 'primary',
                        'completed' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }
}
