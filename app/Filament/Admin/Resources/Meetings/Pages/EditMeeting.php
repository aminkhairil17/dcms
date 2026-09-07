<?php

namespace App\Filament\Admin\Resources\Meetings\Pages;

use App\Filament\Admin\Resources\Meetings\MeetingResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditMeeting extends EditRecord
{
    protected static string $resource = MeetingResource::class;

    public function getTitle(): string
    {
        return 'Ubah Rapat';
    }

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->label('Simpan Perubahan');
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }

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

        // --- LOGIKA TEMPLATE BARU (Sesuai Gambar) ---
        $logoPath = public_path('images/logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/'.pathinfo($logoPath, PATHINFO_EXTENSION).';base64,'.base64_encode($logoData);
        }

        // Extract data that might have just been edited from the form instead of the old record
        $formTitle = $data['title'] ?? $record->title;
        $formDocNumber = $data['doc_number'] ?? ($record->doc_number ?? '-');
        $formAgenda = $data['agenda'] ?? ($record->agenda ?? '-');
        $formLocation = $data['location'] ?? ($record->location ?? '-');
        $formDateTime = $data['date_time'] ?? $record->date_time;

        // Format Tanggal Indonesia
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $dateTime = \Carbon\Carbon::parse($formDateTime);
        $dayName = $days[$dateTime->dayOfWeek];
        $formattedDate = $dayName.', '.$dateTime->day.' '.$months[$dateTime->month].' '.$dateTime->year.' / '.$dateTime->format('H.i').' WITA';

        // Lampiran (Foto/Dokumentasi)
        $attachmentsHtml = '';
        if (! empty($data['attachments'])) {
            $attachmentsHtml .= "<div style='page-break-before: always;'></div>";
            $attachmentsHtml .= "<div style='margin-top: 20px;'>"; // Samakan dengan margin title-section di halaman 1
            $attachmentsHtml .= "<h4 style='text-transform: uppercase; font-size: 14px; text-align: center; margin-bottom: 40px; color: #000;'>LAMPIRAN / DOKUMENTASI</h4>";
            $attachmentsHtml .= "<div style='text-align: center;'>";

            foreach ($data['attachments'] as $attachment) {
                $path = storage_path('app/public/'.$attachment);

                if (file_exists($path)) {
                    $imgData = file_get_contents($path);
                    $extension = pathinfo($path, PATHINFO_EXTENSION);
                    $base64 = 'data:image/'.$extension.';base64,'.base64_encode($imgData);

                    // Gunakan page-break-inside: avoid agar gambar tidak terpotong di tengah halaman
                    $attachmentsHtml .= "<div style='margin-bottom: 50px; page-break-inside: avoid; clear: both;'>";
                    $attachmentsHtml .= "<img src='{$base64}' style='max-width: 90%; max-height: 480px; border: 3px solid #f2f2f2; padding: 5px; background: #fff;'>";
                    $attachmentsHtml .= '</div>';
                }
            }

            $attachmentsHtml .= '</div></div>';
        }

        $notulisName = '-';
        if (! empty($data['notulis_id'])) {
            $notulisUser = \App\Models\User::find($data['notulis_id']);
            if ($notulisUser) {
                $notulisName = $notulisUser->name;
            }
        } elseif (! empty($record->notulis_id)) {
            $notulisUser = \App\Models\User::find($record->notulis_id);
            if ($notulisUser) {
                $notulisName = $notulisUser->name;
            }
        }

        $htmlWithCss = "
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <style>
        @page { margin: 160px 50px 80px 50px; }
        header { position: fixed; top: -145px; left: -50px; right: -50px; height: 140px; }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 60px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 9px; line-height: 1.3; }
        
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; line-height: 1.4; color: #333; }
        
        .header-content { padding: 30px 50px 0 50px; }
        .logo { height: 75px; width: auto; }
        .doc-no { text-align: right; vertical-align: top; font-weight: bold; font-size: 10px; padding-top: 15px; }
        
        .title-section { text-align: center; margin-top: 20px; margin-bottom: 25px; }
        .title-section h3 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .title-section h4 { margin: 5px 0; font-size: 12px; text-transform: uppercase; font-weight: bold; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 4px 0; vertical-align: top; }
        .label { width: 120px; font-weight: bold; }
        .colon { width: 15px; text-align: left; }
        
        /* Style untuk tabel yang dibuat di Rich Editor agar rapi di PDF */
        .content-main table { width: 100%; border-collapse: collapse; margin-top: 5px; page-break-inside: auto; }
        .content-main table tr { page-break-inside: avoid; page-break-after: auto; }
        .content-main table th, .content-main table td { border: 1px solid black; padding: 6px; vertical-align: top; }
        .content-main table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        .content-main table thead { display: table-header-group; }
        
        .footer-table { width: 100%; font-size: 9px; line-height: 1.3; }
        .footer-left { width: 70%; text-align: left; }
        .footer-right { width: 30%; text-align: right; font-weight: bold; vertical-align: bottom; }
    </style>
</head>
<body style='margin-top: -10px;'>
    <header>
        <div style='height: 15px; background: linear-gradient(to right, #4a148c, #d81b60);'></div>
        <div class='header-content'>
            <table width='100%'>
                <tr>
                    <td><img src='{$logoBase64}' class='logo'></td>
                    <td class='doc-no'>No. Dok: {$formDocNumber}</td>
                </tr>
            </table>
        </div>
    </header>

    <footer>
        <table class='footer-table'>
            <tr>
                <td class='footer-left'>
                    info@syifaglobalgroup.com<br>
                    JL. R.O Ulin No. 93, Kec Banjarbaru Selatan<br>
                    Kota Banjarbaru, Kalimantan Selatan, Indonesia 70712
                </td>
                <td class='footer-right'>
                    www.syifaglobalgroup.com
                </td>
            </tr>
        </table>
        <div style='position:absolute; bottom: -12px; left: -50px; right: -50px; height: 8px; background: linear-gradient(to right, #4a148c, #d81b60);'></div>
    </footer>

    <div class='title-section'>
        <h3>NOTULENSI</h3>
        <h4>{$formTitle}</h4>
    </div>

    <table class='info-table'>
        <tr>
            <td class='label'>Perihal</td>
            <td class='colon'>:</td>
            <td>{$formAgenda}</td>
        </tr>
        <tr>
            <td class='label'>Tempat / Lokasi</td>
            <td class='colon'>:</td>
            <td>{$formLocation}</td>
        </tr>
        <tr>
            <td class='label'>Hari / Tanggal</td>
            <td class='colon'>:</td>
            <td>{$formattedDate}</td>
        </tr>
        <tr>
            <td class='label'>Notulis</td>
            <td class='colon'>:</td>
            <td>{$notulisName}</td>
        </tr>
    </table>

    <div class='content-main'>
        ".$contentFromEditor.'
    </div>

    '.$attachmentsHtml.'
</body>
</html>
';

        // --- LOGIKA PEMBUATAN PDF ---
        if (
            ($data['status'] ?? null) === 'completed' &&
            $plainText !== '' && // content tidak kosong
            ($data['mode_notulen'] ?? null) === 'template' // mode = template
        ) {
            // Generate PDF
            $pdf = PDF::loadHTML($htmlWithCss);
            $filename = 'notulen_'.time().'.pdf';

            Storage::disk('private')->put('meetings/'.$filename, $pdf->output());

            $data['file_path'] = 'meetings/'.$filename;
        } else {
            // MODE UPLOAD atau STATUS bukan completed

            if (! $record) {
                // Jika CREATE → kosongkan file_path
                $data['file_path'] = null;
            }
            // Jika EDIT → jangan ubah file lama
        }

        return $data;
    }
}
