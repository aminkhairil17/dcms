<?php

namespace App\Filament\Admin\Resources\DocumentChangeRequestResource\Pages;

use App\Filament\Admin\Resources\DocumentChangeRequestResource;
use App\Notifications\ChangeRequestSubmittedNotification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class CreateDocumentChangeRequest extends CreateRecord
{
    protected static string $resource = DocumentChangeRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('guide')
                ->label('Panduan Usulan Revisi')
                ->icon('heroicon-o-information-circle')
                ->color('info')
                ->modalHeading('Panduan Cara Mengajukan Usulan Revisi')
                ->modalIcon('heroicon-o-pencil-square')
                ->modalWidth('3xl')
                ->modalContent(view('filament.admin.resources.document-change-requests.change-request-guide-modal'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id']            = Auth::id();
        $data['chapter_clause']     = $data['chapter_clause'] ?? '-';
        $data['existing_condition'] = $data['existing_condition'] ?? '';
        $data['status']             = 'pending';
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Notify admins & kabid
        $admins = User::role(['super_admin', 'kabid'])->get();
        if ($admins->isNotEmpty()) {
            try {
                Notification::send($admins, new ChangeRequestSubmittedNotification($record));
            } catch (\Throwable $e) {
                logger()->error('Failed to send ChangeRequestSubmittedNotification: ' . $e->getMessage());
            }
        }
    }
}
