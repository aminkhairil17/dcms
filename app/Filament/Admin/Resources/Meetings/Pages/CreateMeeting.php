<?php

namespace App\Filament\Admin\Resources\Meetings\Pages;

use App\Filament\Admin\Resources\Meetings\MeetingResource;
use Filament\Resources\Pages\CreateRecord;
use App\Mail\MeetingInvitationMail;
use Illuminate\Support\Facades\Mail;

class CreateMeeting extends CreateRecord
{
    protected static string $resource = MeetingResource::class;

    protected static bool $canCreateAnother = false;
    
    public function getTitle(): string
    {
        return 'Tambah Rapat';
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('tutorial')
                ->label('Panduan')
                ->icon('heroicon-o-information-circle')
                ->color('info')
                ->modalHeading('Petunjuk Tambah Rapat')
                ->modalWidth('4xl')
                ->modalContent(view('filament.tutorial-modal', ['image' => 'tambah.jpg']))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),
        ];
    }

    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Buat');
    }


    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }


    protected function afterCreate(): void
    {
        $meeting = $this->record;
        $creatorId = auth()->id();

        // Kirim notifikasi ke creator sebagai konfirmasi di bell
        if ($creatorId) {
            $creator = \App\Models\User::find($creatorId);
            if ($creator) {
                try {
                    $creator->notify(new \App\Notifications\MeetingInvitationNotification($meeting));
                } catch (\Throwable $e) {
                    logger()->error('Failed sending notification to creator: ' . $e->getMessage());
                }
            }
        }

        // Kirim notifikasi ke setiap peserta (kecuali creator agar tidak dobel)
        foreach ($meeting->participants as $user) {
            if ($user->id === $creatorId) {
                continue; // sudah dikirim di atas
            }

            try {
                Mail::to($user->email)->send(new MeetingInvitationMail($meeting));
            } catch (\Throwable $e) {
                logger()->error('Failed sending meeting email to ' . $user->email . ': ' . $e->getMessage());
            }

            try {
                $user->notify(new \App\Notifications\MeetingInvitationNotification($meeting));
            } catch (\Throwable $e) {
                logger()->error('Failed sending meeting notification to ' . $user->email . ': ' . $e->getMessage());
            }
        }
    }
}
