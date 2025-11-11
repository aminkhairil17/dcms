<?php

namespace App\Filament\Admin\Resources\Meetings;

use App\Filament\Admin\Resources\Meetings\Pages\ManageMeetings;
use App\Models\Meeting;
use BackedEnum;
use App\Models\Company;
use App\Models\Department;
use App\Models\Unit;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Hidden;
use UnitEnum;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Meeting Management';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Meeting Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        DateTimePicker::make('meeting_date')
                            ->required()
                            ->minDate(now()),
                        TextInput::make('location')
                            ->maxLength(255)
                            ->placeholder('Online / Ruang Meeting'),
                        Select::make('meeting_type')
                            ->options([
                                'regular' => 'Regular',
                                'urgent' => 'Urgent',
                                'planning' => 'Planning',
                                'review' => 'Review',
                            ])
                            ->default('regular')
                            ->required(),
                    ])->columns(2),

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
                            ->required(),
                    ])->columns(3),

                Section::make('Participants')
                    ->schema([
                        Select::make('invited_users')
                            ->relationship('invitations', 'user_id')
                            ->options(User::where('is_active', true)->pluck('name', 'id'))
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Invite Users'),
                    ]),

                Section::make('Status')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'scheduled' => 'Scheduled',
                                'ongoing' => 'Ongoing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required(),
                        TextInput::make('meeting_code')
                            ->default('MTG-' . strtoupper(uniqid()))
                            ->disabled()
                            ->dehydrated(),
                    ])->columns(2),

                Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('meeting_date')
                    ->dateTime(),
                TextEntry::make('location')
                    ->placeholder('-'),
                TextEntry::make('meeting_code'),
                TextEntry::make('company.name')
                    ->label('Company'),
                TextEntry::make('department.name')
                    ->label('Department'),
                TextEntry::make('unit.name')
                    ->label('Unit'),
                TextEntry::make('created_by')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('meeting_type')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(Meeting $record): bool => $record->trashed()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('meeting')
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('meeting_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('meeting_code')
                    ->searchable(),
                TextColumn::make('company.name')
                    ->searchable(),
                TextColumn::make('department.name')
                    ->searchable(),
                TextColumn::make('unit.name')
                    ->searchable(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('meeting_type')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMeetings::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
