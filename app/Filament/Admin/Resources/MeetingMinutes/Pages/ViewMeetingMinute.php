<?php

namespace App\Filament\Admin\Resources\MeetingMinutes\Pages;

use App\Filament\Admin\Resources\MeetingMinutes\MeetingMinuteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMeetingMinute extends ViewRecord
{
    protected static string $resource = MeetingMinuteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
