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
        return [ViewAction::make(), DeleteAction::make()];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;

        // Ambil raw content dari form
        $contentFromEditor = $data['content'] ?? '';

        // Cek plain text (untuk mengetahui isi kosong/tidak)
        $plainText = trim(strip_tags($contentFromEditor));

        // CSS + Content
        $htmlWithCss =
            "
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }

    h2 { text-align: center; margin-bottom: 10px; }

    p { margin: 2px 0; padding: 0; }

    hr { margin: 10px 0; }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        border: 1px solid black;
        padding: 6px;
        text-align: left;
    }
</style>
" . $contentFromEditor;

        // --- LOGIKA PEMBUATAN PDF ---
        if (
            ($data['status'] ?? null) === 'completed' &&
            $plainText !== '' && // content tidak kosong
            ($data['mode_notulen'] ?? null) === 'template' // mode = template
        ) {
            // Generate PDF
            $pdf = PDF::loadHTML($htmlWithCss);
            $filename = 'notulen_' . time() . '.pdf';

            Storage::disk('private')->put('meetings/' . $filename, $pdf->output());

            $data['file_path'] = 'meetings/' . $filename;
        } else {
            // MODE UPLOAD atau STATUS bukan completed

            if (!$record) {
                // Jika CREATE → kosongkan file_path
                $data['file_path'] = null;
            }
            // Jika EDIT → jangan ubah file lama
        }

        return $data;
    }
}
