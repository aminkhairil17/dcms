<?php

namespace App\Notifications;

use App\Models\DocumentChangeRequest;
use Illuminate\Notifications\Notification;

class ChangeRequestStatusUpdatedNotification extends Notification
{

    public function __construct(public DocumentChangeRequest $changeRequest) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusLabel = match ($this->changeRequest->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => 'Dalam Proses',
        };

        return [
            'type'              => 'change_request_status_updated',
            'change_request_id' => $this->changeRequest->id,
            'document_id'       => $this->changeRequest->document_id,
            'title'             => $this->changeRequest->document->title ?? 'Dokumen',
            'status'            => $this->changeRequest->status,
            'message'           => "Usulan revisi Anda untuk \"{$this->changeRequest->document->title}\" telah {$statusLabel}.",
        ];
    }
}
