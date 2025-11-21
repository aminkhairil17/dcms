<?php

namespace App\Filament\Admin\Resources\Meetings\Schemas;

use Dom\Text;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MeetingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('agenda')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('content')
                    ->html()
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('date_time')
                    ->dateTime(),
                TextEntry::make('location')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('creator.name')
                    ->label('Dibuat Oleh'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
