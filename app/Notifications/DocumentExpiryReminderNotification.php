<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentExpiryReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Document $document,
        public int $daysLeft
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'document_expiry_reminder',
            'document_id' => $this->document->id,
            'title' => $this->document->title,
            'code_number' => $this->document->code_number,
            'expires_at' => $this->document->expires_at?->format('Y-m-d'),
            'days_left' => $this->daysLeft,
            'message' => $this->daysLeft <= 0
                ? 'Dokumen "' . $this->document->title . '" telah melewati masa berlaku.'
                : 'Dokumen "' . $this->document->title . '" akan kedaluwarsa dalam ' . $this->daysLeft . ' hari.',
        ];
    }
}
