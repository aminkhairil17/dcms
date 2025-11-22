<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\Company;
use App\Models\Department;
use App\Models\Unit;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required(),
            TextInput::make('email')->label('Email address')->email()->required(),
            TextInput::make('username')->required()->maxLength(255),
            TextInput::make('password')->password()->required(),
            Select::make('company_id')
                ->label('Company')
                ->options(Company::where('is_active', true)->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->nullable()
                ->live(),
            Select::make('department_id')
                ->label('Department')
                ->options(function (callable $get) {
                    $companyId = $get('company_id');
                    if (!$companyId) {
                        return Department::where('is_active', true)->pluck('name', 'id');
                    }
                    return Department::where('company_id', $companyId)->where('is_active', true)->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->nullable()
                ->live(),
            Select::make('unit_id')
                ->label('Unit')
                ->options(function (callable $get) {
                    $departmentId = $get('department_id');
                    if (!$departmentId) {
                        return Unit::where('is_active', true)->pluck('name', 'id');
                    }
                    return Unit::where('department_id', $departmentId)->where('is_active', true)->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->nullable(),
            Select::make('roles')->relationship('roles', 'name')->multiple()->preload()->searchable(),
            Toggle::make('is_active')->label('Active')->default(true)->required(),
        ]);
    }
}
