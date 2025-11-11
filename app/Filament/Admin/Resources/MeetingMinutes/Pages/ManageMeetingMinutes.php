<?php

namespace App\Filament\Admin\Resources\MeetingMinutes\Pages;

use App\Filament\Admin\Resources\MeetingMinutes\MeetingMinuteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMeetingMinutes extends ManageRecords
{
    protected static string $resource = MeetingMinuteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
