<?php

namespace App\Filament\Admin\Resources\Documents\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Dokumen')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul Dokumen')
                        ->required()
                        ->maxLength(255),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull()
                        ->maxLength(65535),

                    TextInput::make('version')
                        ->label('Versi')
                        ->default('1.0')
                        ->maxLength(10)
                        ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'pending_review' => 'Menunggu Review',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            'archived' => 'Diarsipkan',
                        ])
                        ->default('draft')
                        ->required(),

                    Select::make('confidential_level')
                        ->label('Tingkat Kerahasiaan')
                        ->options([
                            'public' => 'Publik',
                            'internal' => 'Internal',
                            'confidential' => 'Rahasia',
                        ])
                        ->default('internal')
                        ->required(),
                ]),

            Section::make('Upload Dokumen')
                ->schema([
                    FileUpload::make('file_path')
                        ->label('File Dokumen')
                        ->disk('documents')
                        ->preserveFilenames()
                        ->maxSize(10240) // 10 MB
                        ->acceptedFileTypes([
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'image/jpeg',
                            'image/png',
                        ])
                        ->storeFileNamesIn('file_name')
                        ->downloadable()
                        ->openable()
                        ->required(),
                ]),

            Section::make('Relasi Organisasi')
                ->schema([
                    Select::make('company_id')
                        ->label('Perusahaan')
                        ->relationship('company', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live(),

                    Select::make('department_id')
                        ->label('Departemen')
                        ->relationship('department', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live(),

                    Select::make('unit_id')
                        ->label('Unit')
                        ->relationship('unit', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live(),

                    Select::make('category_id')
                        ->label('Kategori Dokumen')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                ]),

            Section::make('Persetujuan')
                ->schema([
                    Select::make('user_id')
                        ->label('Dibuat Oleh')
                        ->relationship('user', 'name')
                        ->required(),

                    Select::make('approver_id')
                        ->label('Disetujui Oleh')
                        ->relationship('approver', 'name'),

                    DateTimePicker::make('approved_at')
                        ->label('Tanggal Persetujuan'),
                ]),
        ]);
    }
}
