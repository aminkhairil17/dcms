<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PersonalReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $reminderTitle,
        public string $reminderNotes
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'personal_reminder',
            'title'   => 'Pengingat Pribadi: ' . $this->reminderTitle,
            'message' => $this->reminderNotes,
            'url'     => route('filament.admin.pages.reminders'),
        ];
    }
}
