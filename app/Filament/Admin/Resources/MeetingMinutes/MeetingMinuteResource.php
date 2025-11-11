<?php

namespace App\Filament\Admin\Resources\MeetingMinutes;

use App\Filament\Admin\Resources\MeetingMinutes\Pages\ManageMeetingMinutes;
use App\Models\MeetingMinute;
use BackedEnum;
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
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Hidden;
use UnitEnum;
use App\Models\Meeting;
use App\Models\User;



class MeetingMinuteResource extends Resource
{
    protected static ?string $model = MeetingMinute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Meeting Management';

    protected static ?string $recordTitleAttribute = 'agenda';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Meeting Selection')
                    ->schema([
                        Select::make('meeting_id')
                            ->label('Meeting')
                            ->options(Meeting::where('status', 'completed')->pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live(),
                        DateTimePicker::make('minute_date')
                            ->required()
                            ->default(now()),
                        Select::make('minute_taker_id')
                            ->label('Minute Taker')
                            ->options(User::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->default(auth()->id())
                            ->required(),
                    ])->columns(3),

                Section::make('Meeting Content')
                    ->schema([
                        Textarea::make('agenda')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label('Meeting Agenda'),
                        Textarea::make('discussion_points')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label('Discussion Points'),
                        Textarea::make('decisions')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label('Decisions Made'),
                        Textarea::make('action_items')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label('Action Items'),
                        Textarea::make('next_meeting_agenda')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->label('Next Meeting Agenda'),
                    ]),

                Section::make('Attendance')
                    ->schema([
                        Select::make('attendees')
                            ->relationship('attendees', 'user_id')
                            ->options(function (callable $get) {
                                $meetingId = $get('meeting_id');
                                if (!$meetingId) return [];

                                $meeting = Meeting::find($meetingId);
                                return $meeting ? $meeting->invitations->pluck('user.name', 'user.id') : [];
                            })
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Attendees (Select who attended)'),
                    ]),

                Section::make('Approval')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'review' => 'Under Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->default('draft')
                            ->required(),
                        Select::make('approved_by')
                            ->options(User::where('is_active', true)->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        DateTimePicker::make('approved_at')
                            ->nullable(),
                    ])->columns(3),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('meeting.title')
                    ->label('Meeting'),
                TextEntry::make('agenda')
                    ->columnSpanFull(),
                TextEntry::make('discussion_points')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('decisions')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('action_items')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('next_meeting_agenda')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('minuteTaker.name')
                    ->label('Minute taker'),
                TextEntry::make('minute_date')
                    ->dateTime(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('approved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn(MeetingMinute $record): bool => $record->trashed()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('agenda')
            ->columns([
                TextColumn::make('meeting.title')
                    ->searchable(),
                TextColumn::make('minuteTaker.name')
                    ->searchable(),
                TextColumn::make('minute_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('approved_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),
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
            'index' => ManageMeetingMinutes::route('/'),
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
