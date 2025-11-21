<?php

namespace App\Filament\Admin\Resources\Meetings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
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
                        RichEditor::make('content')
                            ->label('Content')
                            ->columnSpanFull(),
                        FileUpload::make('file_path')
                            ->label('Notulen (PDF / Word)')
                            ->directory('meetings')
                            ->disk('private')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(2048)
                            ->columnSpanFull(),

                    ])->columnSpanFull(),

                section::make('Status & Schedule')
                    ->schema([
                        DateTimePicker::make('date_time')
                            ->label('Tanggal & Waktu')
                            ->required(),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(255)
                            ->placeholder('Online / Ruang Meeting'),
                        Select::make('status')
                            ->options([
                                'draft' => 'Direncanakan',
                                'ongoing' => 'Berlangsung',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('draft')
                            ->required()
                            ->label('Meeting Status'),
                    ]),

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
