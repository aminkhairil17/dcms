<?php

namespace App\Filament\Admin\Resources\Documents\Tables;

use App\Models\Document;
use Dom\Text;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Action;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code_number')
                    ->label('Code Number')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('document_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'file' => 'success',
                        'form' => 'primary',
                        'hybrid' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'file' => 'File',
                        'form' => 'Form',
                        'hybrid' => 'Both',
                        default => $state,
                    }),
                TextColumn::make('category.prefix')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('company.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'secondary',
                        default => 'gray',
                    }),
                TextColumn::make('user.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('company')
                    ->label('Perusahaan')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('department')
                    ->label('Departemen')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('unit')
                    ->label('Unit')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending_review' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'archived' => 'Archived',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->label('Download')
                    ->url(fn(Document $record) => asset('storage/documents/' . $record->file_path))
                    ->openUrlInNewTab(),

                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(
                        fn($record) =>
                        auth()->user()->hasRole('super_admin') && $record->status === 'draft'
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(
                        fn($record) =>
                        auth()->user()->hasRole('super_admin') && $record->status === 'draft'
                    )
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'rejected',
                            'rejected_by' => auth()->id(),
                            'rejected_at' => now(),
                        ]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
