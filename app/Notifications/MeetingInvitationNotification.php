<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Meeting $meeting
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database', \App\Notifications\Channels\N8nWhatsAppChannel::class];
    }

    /**
     * Payload WhatsApp yang dikirim ke webhook n8n.
     */
    public function toN8n(object $notifiable): array
    {
        return [
            'type' => 'meeting_invitation',
            'title' => 'Undangan Rapat',
            'message' => "Halo {$notifiable->name}, Anda diundang ke rapat \"{$this->meeting->title}\""
                .' pada '.($this->meeting->meeting_date ? $this->meeting->meeting_date->format('d M Y, H:i') : '-')
                .'. Lokasi: '.($this->meeting->location ?? 'Online').'.',
            'url' => url('/admin/meetings/'.$this->meeting->id),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Undangan Rapat: '.$this->meeting->title)
            ->greeting('Halo '.$notifiable->name.'!')
            ->line('Anda diundang untuk menghadiri rapat:')
            ->line('**'.$this->meeting->title.'**')
            ->line('**Tanggal:** '.($this->meeting->meeting_date ? $this->meeting->meeting_date->format('d F Y, H:i') : '-'))
            ->line('**Lokasi:** '.($this->meeting->location ?? 'Online'))
            ->action('Lihat Detail Rapat', url('/admin/meetings/'.$this->meeting->id))
            ->line('Silakan konfirmasi kehadiran Anda melalui sistem.')
            ->salutation('Terima kasih');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'meeting_invitation',
            'meeting_id' => $this->meeting->id,
            'title' => 'Undangan Rapat: '.$this->meeting->title,
            'message' => 'Anda diundang ke rapat "'.$this->meeting->title.'" pada '.($this->meeting->meeting_date ? $this->meeting->meeting_date->format('d M Y, H:i') : '-'),
            'location' => $this->meeting->location ?? 'Online',
        ];
    }
}
