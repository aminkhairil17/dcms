<?php

namespace App\Filament\Admin\Resources;

use App\Models\MeetingLocation;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class MeetingLocationResource extends Resource
{
    protected static ?string $model = MeetingLocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Rapat';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Lokasi Rapat';

    protected static ?string $modelLabel = 'Lokasi Rapat';

    protected static ?string $pluralModelLabel = 'Lokasi Rapat';

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        return $user?->hasAnyRole(['super_admin', 'kabid', 'direktur']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nama Lokasi')
                ->required()
                ->maxLength(255)
                ->placeholder('Contoh: Ruang Meeting A, Aula Lantai 2'),

            TextInput::make('address')
                ->label('Alamat / Keterangan')
                ->maxLength(255)
                ->placeholder('Contoh: Gedung Utama Lt. 3')
                ->nullable(),

            TextInput::make('capacity')
                ->label('Kapasitas (Orang)')
                ->numeric()
                ->minValue(1)
                ->nullable()
                ->placeholder('Contoh: 20'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Mobile card
                TextColumn::make('name')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable()
                    ->view('filament.tables.columns.meeting-location-mobile-card')
                    ->grow(),

                // Desktop columns
                TextColumn::make('address')
                    ->label('Alamat / Keterangan')
                    ->placeholder('—')
                    ->limit(50)
                    ->visibleFrom('md'),

                TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->placeholder('—')
                    ->suffix(' orang')
                    ->alignCenter()
                    ->width('100px')
                    ->visibleFrom('md'),

                \Filament\Tables\Columns\TextColumn::make('status_ruangan')
                    ->label('Status')
                    ->badge()
                    ->state(function (MeetingLocation $record): string {
                        return $record->isCurrentlyInUse() ? 'Sedang Dipakai' : 'Tersedia';
                    })
                    ->color(function (MeetingLocation $record): string {
                        return $record->isCurrentlyInUse() ? 'danger' : 'success';
                    })
                    ->icon(function (MeetingLocation $record): string {
                        return $record->isCurrentlyInUse()
                            ? 'heroicon-o-lock-closed'
                            : 'heroicon-o-check-circle';
                    })
                    ->tooltip(function (MeetingLocation $record): ?string {
                        $meeting = $record->getCurrentMeeting();
                        return $meeting ? $meeting->title : null;
                    })
                    ->visibleFrom('md'),

                \Filament\Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Mulai')
                    ->icon('heroicon-o-play-circle')
                    ->iconColor('info')
                    ->state(function (MeetingLocation $record): string {
                        $meeting = $record->getCurrentMeeting();
                        return $meeting ? $meeting->date_time->format('H:i') : '—';
                    })
                    ->color(fn (MeetingLocation $record) => $record->isCurrentlyInUse() ? 'info' : 'gray')
                    ->alignCenter()
                    ->visibleFrom('md'),

                \Filament\Tables\Columns\TextColumn::make('jam_berakhir')
                    ->label('Berakhir')
                    ->icon('heroicon-o-stop-circle')
                    ->iconColor('info')
                    ->state(function (MeetingLocation $record): string {
                        $meeting = $record->getCurrentMeeting();
                        if (!$meeting) return '—';
                        return $meeting->end_time
                            ? $meeting->end_time->format('H:i')
                            : '—';
                    })
                    ->color(fn (MeetingLocation $record) => $record->isCurrentlyInUse() ? 'info' : 'gray')
                    ->alignCenter()
                    ->visibleFrom('md'),

                TextColumn::make('meetings_count')
                    ->label('Total Rapat')
                    ->counts('meetings')
                    ->badge()
                    ->color('gray')
                    ->alignCenter()
                    ->width('100px')
                    ->visibleFrom('md'),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('available')
                    ->label('Tersedia')
                    ->query(fn ($query) => $query->available()),

                \Filament\Tables\Filters\Filter::make('occupied')
                    ->label('Sedang Dipakai')
                    ->query(fn ($query) => $query->occupied()),
            ])
            ->recordActions([
                EditAction::make()->button()->outlined()->size('xs'),
                DeleteAction::make()->button()->outlined()->size('xs'),
            ])
            ->emptyStateIcon('heroicon-o-map-pin')
            ->emptyStateHeading('Belum ada lokasi rapat')
            ->emptyStateDescription('Tambahkan lokasi rapat yang sering digunakan.')
            ->striped()
            ->poll('60s'); // refresh otomatis setiap 60 detik
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\MeetingLocationResource\Pages\ListMeetingLocations::route('/'),
        ];
    }
}
