<?php

namespace App\Filament\Admin\Resources\Documents\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use App\Models\Company;
use App\Models\Department;
use App\Models\DocumentCategory;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Document Information')
                ->schema([
                    Select::make('document_type')
                        ->options([
                            'file' => 'Upload File',
                            'form' => 'Create from Form',
                        ])
                        ->default('file')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, $set) {
                            // Reset fields ketika type berubah
                            if ($state === 'file') {
                                $set('content', null);
                            } elseif ($state === 'form') {
                                $set('file_path', null);
                            }
                        }),
                    TextInput::make('Code Number')
                        ->label('Code Number')
                        ->maxLength(255),
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                ])->columnSpanFull(),
            FileUpload::make('file_path')
                ->label('Document File')
                ->disk('documents')
                ->directory('documents')
                ->preserveFilenames()
                ->maxSize(10240)
                ->acceptedFileTypes([
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'image/jpeg',
                    'image/png',
                ])
                ->required(fn($get) => in_array($get('document_type'), ['file', 'hybrid']))
                ->hidden(fn($get) => $get('document_type') === 'form')
                ->helperText(fn($get) => $get('document_type') === 'hybrid' ? 'Optional: Upload supporting file' : 'Upload document file')
                ->columnSpanFull(),
            Section::make('Document Content')
                ->schema([
                    RichEditor::make('content')
                        ->maxLength(65535)
                        ->required(fn($get) => in_array($get('document_type'), ['form', 'hybrid']))
                        ->hidden(fn($get) => $get('document_type') === 'file')
                        ->helperText('Create document content using rich text editor')
                        ->columnSpanFull(),
                ])
                ->hidden(fn($get) => $get('document_type') === 'file')
                ->columnSpanFull(),
            Section::make('Organization')
                ->schema([
                    Select::make('company_id')
                        ->label('Company')
                        ->options(fn() => Company::where('is_active', true)->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->default(fn() => optional(auth()->user())->company_id ?? null)
                        ->required(),

                    Select::make('department_id')
                        ->label('Department')
                        ->options(function (callable $get) {
                            $companyId = $get('company_id') ?? optional(auth()->user())->company_id;
                            return Department::when($companyId, fn($q) => $q->where('company_id', $companyId))
                                ->where('is_active', true)
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->default(fn() => optional(auth()->user())->department_id ?? null)
                        ->required(),

                    Select::make('unit_id')
                        ->label('Unit')
                        ->options(function (callable $get) {
                            $departmentId = $get('department_id') ?? optional(auth()->user())->department_id;
                            return Unit::when($departmentId, fn($q) => $q->where('department_id', $departmentId))
                                ->where('is_active', true)
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->default(fn() => optional(auth()->user())->unit_id ?? null)
                        ->required(),

                    Select::make('category_id')
                        ->label('Category')
                        ->options(function (callable $get) {
                            return DocumentCategory::where('is_active', true)
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->required(),
                ]),

            Section::make('Settings')
                ->schema([
                    Select::make('status')
                        ->options(function () {
                            if (auth()->user()->hasRole('Direktur')) {
                                // Kalau Direktur -> boleh semua
                                return [
                                    'draft' => 'Draft',
                                    'published' => 'Published',
                                    'archived' => 'Archived',
                                ];
                            }

                            // Bukan Direktur -> hilangkan pilihan "Published"
                            return [
                                'draft' => 'Draft',
                                'archived' => 'Archived',
                            ];
                        })
                        ->default('draft')
                        ->required(),
                ]),
        ]);
    }
}
