<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentCreatedNotification extends Notification
{

    public function __construct(
        public Document $document
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $creator = $this->document->user;
        $department = $this->document->department;

        return (new MailMessage)
            ->subject('Dokumen Baru Dibuat: ' . $this->document->title)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Dokumen baru telah ditambahkan ke sistem.')
            ->line('---')
            ->line('**Judul:** ' . $this->document->title)
            ->line('**Nomor Kode:** ' . $this->document->code_number)
            ->line('**Dibuat oleh:** ' . ($creator?->name ?? 'Unknown'))
            ->line('**Departemen:** ' . ($department?->name ?? '-'))
            ->line('---')
            ->action('Lihat Dokumen', url('/admin/documents/' . $this->document->id))
            ->salutation('Salam, Sistem DCMS');
    }

    public function toArray(object $notifiable): array
    {
        $isCreator = $notifiable->id === ($this->document->user_id);

        return [
            'type' => 'document_created',
            'document_id' => $this->document->id,
            'title' => $this->document->title,
            'code_number' => $this->document->code_number,
            'submitted_by' => $this->document->user?->name,
            'message' => $isCreator
                ? 'Dokumen "' . $this->document->title . '" berhasil diunggah.'
                : 'Dokumen baru "' . $this->document->title . '" telah ditambahkan oleh ' . ($this->document->user?->name ?? 'seseorang') . '.',
        ];
    }
}
