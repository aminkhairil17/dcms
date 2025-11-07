<?php

namespace App\Filament\Admin\Resources\Documents\Tables;

use App\Models\Document;
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
                TextColumn::make('title')
                    ->label('Judul Dokumen')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('department.name')
                    ->label('Departemen')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('unit.name')
                    ->label('Unit')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn($record) => $record->category->color ?? 'gray'),

                TextColumn::make('file_name')
                    ->label('File')
                    ->url(fn($record) => asset('storage/documents/' . $record->file_path))
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->limit(20),

                TextColumn::make('version')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending_review' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'archived' => 'secondary',
                        default => 'gray',
                    }),

                TextColumn::make('confidential_level')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'public' => 'success',
                        'internal' => 'primary',
                        'confidential' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('user.name')
                    ->label('Uploaded By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Diupload')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
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

                SelectFilter::make('confidential_level')
                    ->options([
                        'public' => 'Public',
                        'internal' => 'Internal',
                        'confidential' => 'Confidential',
                    ]),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->label('Download')
                    ->url(fn(Document $record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),
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
