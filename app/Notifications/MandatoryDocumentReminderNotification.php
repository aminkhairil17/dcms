<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MandatoryDocumentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Document $document
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'mandatory_document_reminder',
            'title'       => 'Pengingat Dokumen Wajib Baca',
            'message'     => 'Anda memiliki dokumen wajib baca "' . $this->document->title . '" yang belum dibaca.',
            'document_id' => $this->document->id,
            'url'         => route('filament.admin.pages.compliance-hub'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengingat Wajib Baca: ' . $this->document->title)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Anda belum membaca dan mengonfirmasi dokumen wajib berikut:')
            ->line('**' . $this->document->title . '** (' . ($this->document->code_number ?? 'SOP') . ')')
            ->action('Buka Compliance Hub', route('filament.admin.pages.compliance-hub'))
            ->line('Silakan buka Compliance Hub dan konfirmasi telah membaca dokumen tersebut.')
            ->salutation('Terima kasih');
    }
}
