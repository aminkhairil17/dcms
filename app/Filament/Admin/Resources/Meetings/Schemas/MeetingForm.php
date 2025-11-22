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
        return $schema->components([
            Section::make('Meeting Information')
                ->schema([
                    TextInput::make('title')->label('Judul Rapat')->required()->maxLength(255),
                    Textarea::make('agenda')->label('Agenda')->rows(3)->columnSpanFull(),
                    Select::make('mode_notulen')
                        ->label('Metode Notulensi')
                        ->options([
                            'template' => 'Gunakan Template',
                            'upload' => 'Upload File PDF',
                        ])
                        ->default('template')
                        ->reactive()
                        ->default(function ($record) {
                            if (!$record) {
                                return 'template';
                            } // saat create

                            // Cek content
                            $contentText = trim(strip_tags($record->content));

                            if ($contentText !== '') {
                                return 'template'; // ada isi → template
                            }

                            // content kosong + file_path ada → upload
                            if (!empty($record->file_path)) {
                                return 'upload';
                            }

                            // dua-duanya kosong → template
                            return 'template';
                        })
                        ->afterStateHydrated(function ($set, $record) {
                            if (!$record) {
                                // Saat Create → default template
                                $set('mode_notulen', 'template');
                                return;
                            }

                            // Cek content
                            $contentText = trim(strip_tags($record->content));
                            if ($contentText !== '') {
                                $set('mode_notulen', 'template');
                                return;
                            }

                            // Cek file_path
                            if (!empty($record->file_path)) {
                                $set('mode_notulen', 'upload');
                                return;
                            }

                            // Dua-duanya kosong → template
                            $set('mode_notulen', 'template');
                        })
                        ->required(),

                    // TAMPIL JIKA PILIH TEMPLATE
                    RichEditor::make('content')
                        ->label('Content / Notulensi')
                        ->columnSpanFull()
                        ->visible(fn($get) => $get('mode_notulen') === 'template')
                        ->afterStateHydrated(function ($set, $state, $record) {
                            // Saat CREATE → state kosong, jangan isi apa pun
                            if (!$record) {
                                return;
                            }

                            // Cek apakah content SUDAH ada isi
                            $plain = trim(strip_tags($state));

                            if ($plain !== '') {
                                // Sudah ada isi → jangan ganti, tampilkan apa adanya
                                return;
                            }

                            // Content kosong → generate template otomatis
                            $set(
                                'content',
                                "
            <h2 style='text-align:center;'>NOTULENSI RAPAT</h2>

            <p><strong>Agenda:</strong> {$record->agenda}</p>
            <p><strong>Tanggal:</strong> {$record->date_time}</p>
            <p><strong>Tempat:</strong> {$record->location}</p>

            <hr>

            <h3>Pembahasan</h3>

            <table width='100%' border='1' style='border-collapse: collapse;'>
            <tr>
                <th>No</th>
                <th>Pembahasan</th>
                <th>Action Plan</th>
                <th>PIC</th>
            </tr>
            <tr><td>1</td><td></td><td></td><td></td></tr>
            </table>
        ",
                            );
                        }),

                    // TAMPIL JIKA PILIH UPLOAD
                    FileUpload::make('file_path')
                        ->label('Notulen (PDF / Word)')
                        ->directory('meetings')
                        ->disk('private')
                        ->acceptedFileTypes([
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ])
                        ->maxSize(2048)
                        ->columnSpanFull()
                        ->visible(fn($get) => $get('mode_notulen') === 'upload'),
                ])
                ->columnSpanFull(),

            section::make('Status & Schedule')->schema([
                DateTimePicker::make('date_time')->label('Tanggal & Waktu')->required(),
                TextInput::make('location')->label('Lokasi')->maxLength(255)->placeholder('Online / Ruang Meeting'),
                Select::make('status')
                    ->options([
                        'scheduled' => 'Dijadwalkan',
                        'ongoing' => 'Berlangsung',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('draft')
                    ->required()
                    ->label('Meeting Status'),
            ]),

            Section::make('Participants')->schema([
                Select::make('participants')
                    ->relationship('participants', 'name')
                    ->options(User::where('is_active', true)->pluck('name', 'id'))
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->required()
                    ->label('Select Participants'),
            ]),

            Hidden::make('created_by')->default(auth()->id()),
        ]);
    }
}
