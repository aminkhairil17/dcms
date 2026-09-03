<?php

namespace App\Notifications;

use App\Models\DocumentChangeRequest;
use Illuminate\Notifications\Notification;

class ChangeRequestSubmittedNotification extends Notification
{

    public function __construct(public DocumentChangeRequest $changeRequest) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'              => 'change_request_submitted',
            'change_request_id' => $this->changeRequest->id,
            'document_id'       => $this->changeRequest->document_id,
            'title'             => $this->changeRequest->document->title ?? 'Dokumen',
            'submitted_by'      => $this->changeRequest->user->name ?? '—',
            'chapter_clause'    => $this->changeRequest->chapter_clause,
            'message'           => 'Usulan revisi baru untuk dokumen "' . ($this->changeRequest->document->title ?? 'Dokumen') . '" oleh ' . ($this->changeRequest->user->name ?? '—') . ' telah diajukan.',
        ];
    }
}
