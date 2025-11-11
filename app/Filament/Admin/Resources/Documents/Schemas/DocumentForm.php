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

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Document Type')
                ->schema([
                    Select::make('document_type')
                        ->options([
                            'file' => 'Upload File',
                            'form' => 'Create from Form',
                            'hybrid' => 'Both File & Form',
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
                ])->columns(1),

            Section::make('Document Information')
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ]),

            Section::make('File Upload')
                ->schema([
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
                        ->helperText(fn($get) => $get('document_type') === 'hybrid' ? 'Optional: Upload supporting file' : 'Upload document file'),
                ]),

            Section::make('Document Content')
                ->schema([
                    RichEditor::make('content')
                        ->toolbarButtons([
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->maxLength(65535)
                        ->required(fn($get) => in_array($get('document_type'), ['form', 'hybrid']))
                        ->hidden(fn($get) => $get('document_type') === 'file')
                        ->helperText('Create document content using rich text editor')
                        ->columnSpanFull(),
                ])
                ->hidden(fn($get) => $get('document_type') === 'file'),

            Section::make('Organization')
                ->schema([
                    Select::make('company_id')
                        ->label('Company')
                        ->options(Company::where('is_active', true)->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live(),
                    Select::make('department_id')
                        ->label('Department')
                        ->options(function (callable $get) {
                            $companyId = $get('company_id');
                            if (!$companyId) return [];
                            return Department::where('company_id', $companyId)
                                ->where('is_active', true)
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live(),
                    Select::make('unit_id')
                        ->label('Unit')
                        ->options(function (callable $get) {
                            $departmentId = $get('department_id');
                            if (!$departmentId) return [];
                            return Unit::where('department_id', $departmentId)
                                ->where('is_active', true)
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live(),
                    Select::make('category_id')
                        ->label('Category')
                        ->options(function (callable $get) {
                            $companyId = $get('company_id');
                            if (!$companyId) {
                                return DocumentCategory::where('is_active', true)->pluck('name', 'id');
                            }
                            return DocumentCategory::where('company_id', $companyId)
                                ->where('is_active', true)
                                ->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->required(),
                ])->columns(3),

            Section::make('Settings')
                ->schema([
                    Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'archived' => 'Archived',
                        ])
                        ->default('draft')
                        ->required(),
                    Select::make('confidential_level')
                        ->options([
                            'public' => 'Public',
                            'internal' => 'Internal',
                            'confidential' => 'Confidential',
                        ])
                        ->default('internal')
                        ->required(),
                ])->columns(2),
        ]);
    }
}
