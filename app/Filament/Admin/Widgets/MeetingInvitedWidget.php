<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Meeting;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;

class MeetingInvitedWidget extends BaseTableWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Undangan Rapat Mendatang';

    public function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->query(
                Meeting::query()
                    ->where(function ($query) use ($user) {
                        $query->whereHas('participants', function ($q) use ($user) {
                            $q->where('users.id', $user->id);
                        })
                            ->orWhere('created_by', $user->id);
                    })
                    ->where('date_time', '>=', now())
                    ->whereNotIn('status', ['cancelled', 'completed'])
                    ->orderBy('date_time', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Rapat')
                    ->limit(30),
                Tables\Columns\TextColumn::make('date_time')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->limit(20),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25]);
    }
}
