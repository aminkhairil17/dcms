<?php

namespace App\Filament\Admin\Resources\Documents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('file_path')
                    ->required(),
                TextInput::make('file_name')
                    ->required(),
                TextInput::make('file_size')
                    ->required(),
                TextInput::make('version')
                    ->required()
                    ->default('1.0'),
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->required(),
                Select::make('unit_id')
                    ->relationship('unit', 'name')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('status')
                    ->options([
            'draft' => 'Draft',
            'pending_review' => 'Pending review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'archived' => 'Archived',
        ])
                    ->default('draft')
                    ->required(),
                Select::make('confidential_level')
                    ->options(['public' => 'Public', 'internal' => 'Internal', 'confidential' => 'Confidential'])
                    ->default('internal')
                    ->required(),
                Select::make('approver_id')
                    ->relationship('approver', 'name'),
                DateTimePicker::make('approved_at'),
            ]);
    }
}
