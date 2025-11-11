<?php

namespace App\Filament\Admin\Resources\MeetingMinutes\Pages;

use App\Filament\Admin\Resources\MeetingMinutes\MeetingMinuteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMeetingMinute extends CreateRecord
{
    protected static string $resource = MeetingMinuteResource::class;
}
