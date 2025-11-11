<?php

namespace App\Notifications;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Meeting $meeting
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengingat Meeting: ' . $this->meeting->title)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Ini adalah pengingat untuk meeting:')
            ->line('**' . $this->meeting->title . '**')
            ->line('**Tanggal:** ' . $this->meeting->meeting_date->format('d F Y H:i'))
            ->line('**Lokasi:** ' . ($this->meeting->location ?? 'Online'))
            ->action('Lihat Detail Meeting', url('/meetings/' . $this->meeting->id))
            ->line('Meeting akan segera dimulai!')
            ->salutation('Terima kasih');
    }
}
