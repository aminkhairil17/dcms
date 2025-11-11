<?php

namespace App\Filament\Admin\Resources\MeetingMinutes;

use App\Filament\Admin\Resources\MeetingMinutes\Pages\CreateMeetingMinute;
use App\Filament\Admin\Resources\MeetingMinutes\Pages\EditMeetingMinute;
use App\Filament\Admin\Resources\MeetingMinutes\Pages\ListMeetingMinutes;
use App\Filament\Admin\Resources\MeetingMinutes\Pages\ViewMeetingMinute;
use App\Filament\Admin\Resources\MeetingMinutes\Schemas\MeetingMinuteForm;
use App\Filament\Admin\Resources\MeetingMinutes\Schemas\MeetingMinuteInfolist;
use App\Filament\Admin\Resources\MeetingMinutes\Tables\MeetingMinutesTable;
use App\Models\MeetingMinute;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MeetingMinuteResource extends Resource
{
    protected static ?string $model = MeetingMinute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Rapat';

    protected static ?string $navigationLabel = 'Notulen Rapat';

    protected static ?string $recordTitleAttribute = 'content';

    public static function form(Schema $schema): Schema
    {
        return MeetingMinuteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MeetingMinuteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MeetingMinutesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMeetingMinutes::route('/'),
            'create' => CreateMeetingMinute::route('/create'),
            'view' => ViewMeetingMinute::route('/{record}'),
            'edit' => EditMeetingMinute::route('/{record}/edit'),
        ];
    }
}
