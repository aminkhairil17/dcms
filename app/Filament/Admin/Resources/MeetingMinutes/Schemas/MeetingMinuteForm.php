<?php

namespace App\Filament\Admin\Resources\MeetingMinutes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MeetingMinuteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('meeting_id')
                    ->relationship('meeting', 'title')
                    ->required(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('action_items'),
                TextInput::make('decisions'),
                TextInput::make('mentioned_users'),
            ]);
    }
}
