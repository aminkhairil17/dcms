<?php

namespace App\Filament\Reviewer\Resources\ReviewDocumentResource\Pages;

use App\Filament\Reviewer\Resources\ReviewDocumentResource;
use App\Models\Document;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;

class ViewReviewDocument extends ViewRecord
{
    protected static string $resource = ReviewDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve_kabid')
                ->label('Setujui Dokumen')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->authorize('review')
                ->visible(function (Document $record) {
                    /** @var User $user */
                    $user = Auth::user();

                    return $record->status === Document::STATUS_PENDING_KABID && $user->hasRole('kabid');
                })
                ->modalDescription('Dokumen ini akan diteruskan ke Direktur untuk keputusan akhir.')
                ->modalSubmitActionLabel('Ya, Setujui')
                ->modalCancelActionLabel('Batal')
                ->form([
                    Textarea::make('kabid_notes')
                        ->label('Catatan (opsional)')
                        ->placeholder('Tambahkan catatan untuk Direktur...')
                        ->rows(3),
                ])
                ->action(function (Document $record, array $data) {
                    /** @var User $user */
                    $user = Auth::user();
                    $record->approveByKabid($user, $data['kabid_notes'] ?? null);

                    \Filament\Notifications\Notification::make()
                        ->title('Dokumen disetujui')
                        ->success()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            Action::make('reject_kabid')
                ->label('Tolak Dokumen')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->authorize('review')
                ->visible(function (Document $record) {
                    /** @var User $user */
                    $user = Auth::user();

                    return $record->status === Document::STATUS_PENDING_KABID && $user->hasRole('kabid');
                })
                ->requiresConfirmation()
                ->modalHeading('Tolak Dokumen')
                ->modalDescription('Dokumen akan ditolak dan staff akan diberitahu.')
                ->modalSubmitActionLabel('Ya, Tolak')
                ->modalCancelActionLabel('Batal')
                ->form([
                    Textarea::make('kabid_notes')
                        ->label('Alasan Penolakan')
                        ->placeholder('Jelaskan alasan penolakan...')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (Document $record, array $data) {
                    /** @var User $user */
                    $user = Auth::user();
                    $record->rejectByKabid($user, $data['kabid_notes']);

                    \Filament\Notifications\Notification::make()
                        ->title('Dokumen ditolak')
                        ->danger()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            Action::make('approve_direktur')
                ->label('Setujui Dokumen (Final)')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->authorize('review')
                ->visible(function (Document $record) {
                    /** @var User $user */
                    $user = Auth::user();

                    return $record->status === Document::STATUS_PENDING_DIREKTUR && $user->hasRole('direktur');
                })
                ->requiresConfirmation()
                ->modalHeading('Setujui Dokumen — Keputusan Akhir')
                ->modalDescription('Dokumen akan disetujui dan status menjadi APPROVED.')
                ->modalSubmitActionLabel('Ya, Setujui')
                ->modalCancelActionLabel('Batal')
                ->form([
                    Textarea::make('direktur_notes')
                        ->label('Catatan (opsional)')
                        ->placeholder('Tambahkan catatan...')
                        ->rows(3),
                ])
                ->action(function (Document $record, array $data) {
                    /** @var User $user */
                    $user = Auth::user();
                    $record->approveByDirektur($user, $data['direktur_notes'] ?? null);

                    \Filament\Notifications\Notification::make()
                        ->title('Dokumen disetujui final')
                        ->success()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            Action::make('reject_direktur')
                ->label('Tolak Dokumen (Final)')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->authorize('review')
                ->visible(function (Document $record) {
                    /** @var User $user */
                    $user = Auth::user();

                    return $record->status === Document::STATUS_PENDING_DIREKTUR && $user->hasRole('direktur');
                })
                ->requiresConfirmation()
                ->modalHeading('Tolak Dokumen — Keputusan Akhir')
                ->modalDescription('Dokumen akan ditolak dan staff akan diberitahu.')
                ->modalSubmitActionLabel('Ya, Tolak')
                ->modalCancelActionLabel('Batal')
                ->form([
                    Textarea::make('direktur_notes')
                        ->label('Alasan Penolakan')
                        ->placeholder('Jelaskan alasan penolakan...')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (Document $record, array $data) {
                    /** @var User $user */
                    $user = Auth::user();
                    $record->rejectByDirektur($user, $data['direktur_notes']);

                    \Filament\Notifications\Notification::make()
                        ->title('Dokumen ditolak final')
                        ->danger()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
}
