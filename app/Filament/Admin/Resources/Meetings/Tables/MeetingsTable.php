<?php

namespace App\Filament\Admin\Resources\Meetings\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;


class MeetingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Rapat')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('date_time')
                    ->label('Tanggal & Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'planned' => 'Direncanakan',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('download')
                    ->label('Hasil Notulen')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn($record) => !empty($record->file_path))
                    ->action(function ($record) {

                        // Path file di storage private
                        $filePath = storage_path('app/private/' . $record->file_path);

                        if (! file_exists($filePath)) {
                            Notification::make()
                                ->title('File tidak ditemukan.')
                                ->danger()
                                ->send();

                            return;
                        }

                        return response()->streamDownload(function () use ($filePath) {
                            echo file_get_contents($filePath);
                        }, basename($filePath));
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
