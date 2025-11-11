<?php

namespace App\Filament\Admin\Resources\MeetingMinutes\Pages;

use App\Filament\Admin\Resources\MeetingMinutes\MeetingMinuteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMeetingMinute extends EditRecord
{
    protected static string $resource = MeetingMinuteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
