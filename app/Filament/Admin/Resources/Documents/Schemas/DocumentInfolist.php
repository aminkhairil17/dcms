<?php

namespace App\Filament\Admin\Resources\Documents\Schemas;

use App\Models\Document;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DocumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('file_path'),
                TextEntry::make('file_name'),
                TextEntry::make('file_size'),
                TextEntry::make('version'),
                TextEntry::make('company.name')
                    ->label('Company'),
                TextEntry::make('department.name')
                    ->label('Department'),
                TextEntry::make('unit.name')
                    ->label('Unit'),
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('confidential_level')
                    ->badge(),
                TextEntry::make('approver.name')
                    ->label('Approver')
                    ->placeholder('-'),
                TextEntry::make('approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Document $record): bool => $record->trashed()),
            ]);
    }
}
