<?php

namespace App\Notifications;

use App\Models\DocumentDiscussion;
use Illuminate\Notifications\Notification;

class DiscussionPinnedNotification extends Notification
{
    public function __construct(public DocumentDiscussion $discussion) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'discussion_pinned',
            'discussion_id' => $this->discussion->id,
            'document_id' => $this->discussion->document_id,
            'title' => $this->discussion->document->title ?? 'Dokumen',
            'pinned_by' => $this->discussion->user->name ?? '—',
            'message' => 'Jawaban Anda telah disematkan sebagai Jawaban Resmi pada diskusi dokumen "'.($this->discussion->document->title ?? 'Dokumen').'".',
        ];
    }
}
