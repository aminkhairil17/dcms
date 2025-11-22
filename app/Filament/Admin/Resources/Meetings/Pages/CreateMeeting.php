<?php

namespace App\Filament\Admin\Resources\Meetings\Pages;

use App\Filament\Admin\Resources\Meetings\MeetingResource;
use Filament\Resources\Pages\CreateRecord;
use App\Mail\MeetingInvitationMail;
use Illuminate\Support\Facades\Mail;

class CreateMeeting extends CreateRecord
{
    protected static string $resource = MeetingResource::class;

    protected function afterCreate(): void
    {
        $meeting = $this->record;

        foreach ($meeting->participants as $user) {
            Mail::to($user->email)->send(new MeetingInvitationMail($meeting));
        }
    }
}
