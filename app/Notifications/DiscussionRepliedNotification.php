<?php

namespace App\Notifications;

use App\Models\DocumentDiscussion;
use Illuminate\Notifications\Notification;

class DiscussionRepliedNotification extends Notification
{
    public function __construct(public DocumentDiscussion $discussion) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'discussion_replied',
            'discussion_id' => $this->discussion->id,
            'document_id' => $this->discussion->document_id,
            'title' => $this->discussion->document->title ?? 'Dokumen',
            'replied_by' => $this->discussion->user->name ?? '—',
            'message' => ($this->discussion->user->name ?? 'Seseorang').' membalas diskusi Anda pada dokumen "'.($this->discussion->document->title ?? 'Dokumen').'".',
        ];
    }
}
