<?php

namespace App\Filament\Admin\Resources\DocumentCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DocumentCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('prefix')
                    ->label('Prefix')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
