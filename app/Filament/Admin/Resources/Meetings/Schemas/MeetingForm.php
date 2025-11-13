<?php

namespace App\Filament\Admin\Resources\Meetings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Hidden;
use App\Models\User;

class MeetingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Meeting Information')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Rapat')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('agenda')
                            ->label('Agenda')
                            ->rows(3)
                            ->columnSpanFull(),
                        DateTimePicker::make('date_time')
                            ->label('Tanggal & Waktu')
                            ->required()
                            ->minDate(now()),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(255)
                            ->placeholder('Online / Ruang Meeting'),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'ongoing' => 'Berlangsung',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('draft')
                            ->required()
                            ->label('Meeting Status'),
                    ])->columns(1),

                Section::make('Participants')
                    ->schema([
                        Select::make('participants')
                            ->relationship('participants', 'name')
                            ->options(User::where('is_active', true)->pluck('name', 'id'))
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                            ->label('Select Participants'),
                    ]),

                Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }
}
