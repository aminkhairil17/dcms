<?php

namespace App\Filament\Admin\Resources\Meetings\Pages;

use App\Filament\Admin\Resources\Meetings\MeetingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class EditMeeting extends EditRecord
{
    protected static string $resource = MeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['content'])) {

            $pdf = PDF::loadHTML($data['content']);
            $filename = 'notulen_' . time() . '.pdf';

            Storage::disk('private')->put('meetings/' . $filename, $pdf->output());

            $data['file_path'] = 'meetings/' . $filename;
        }

        return $data;
    }
}
