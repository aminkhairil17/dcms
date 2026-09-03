<?php

namespace App\Filament\Admin\Resources\Units\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('prefix')
                    ->label('Kode')
                    ->required(),
                Select::make('company_id')
                    ->label('Perusahaan')
                    ->options(\App\Models\Company::where('is_active', true)->pluck('name', 'id'))
                    ->live()
                    ->afterStateUpdated(fn ($set) => $set('department_id', null))
                    ->formatStateUsing(fn ($record) => $record?->department?->company_id)
                    ->dehydrated(false)
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('department_id')
                    ->label('Departemen')
                    ->relationship('department', 'name', fn (\Illuminate\Database\Eloquent\Builder $query, $get) => $query->where('company_id', $get('company_id'))->where('is_active', true))
                    ->live()
                    ->searchable()
                    ->preload()
                    ->default(session('last_department_id'))
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}
